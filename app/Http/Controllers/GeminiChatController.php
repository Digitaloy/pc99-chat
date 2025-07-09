<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Message;

class GeminiChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->take(50)->get()->reverse();
        return view('chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Save user message
        Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_bot' => false,
        ]);

        // Get AI reply from Gemini
        $reply = $this->getGeminiReply($request->message);

        // Save bot reply
        Message::create([
            'user_id' => Auth::id(),
            'message' => $reply,
            'is_bot' => true,
        ]);

        return redirect()->route('chat.index');
    }

    private function getGeminiReply($userInput)
    {
        $apiKey = env('GEMINI_API_KEY');

        $systemInstructions = <<<EOT
You are an expert support assistant for the Bangladeshi casino site PC99.
Always reply in Bengali language.
Use the following casino rules to answer questions:

1. Withdraw time: 9 AM - 11 PM
2. Minimum withdraw: 500 BDT
3. Deposit is instant via bKash/Nagad
4. First deposit gets 20% bonus
5. To play, users must log in and enter the lobby
6. Fishing and slot games are available
7. Withdraw only allowed if wallet balance > 0
8. PC99 admins manually approve all withdrawals
casino er malik ba owner Abdul Ahad, Se nijei ei site er malik ba creator o owner.
Answer only based on the above rules.
If you don’t know, say “আমি দুঃখিত, এই বিষয়ে আমার কোনো তথ্য নেই।”
EOT;

        $response = Http::post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $systemInstructions . "\n\nUser: " . $userInput]
                    ]
                ]
            ]
        ]);

        if (!$response->successful()) {
            return "AI Bot: সমস্যাটি হচ্ছে, দয়া করে একটু পরে আবার চেষ্টা করুন।";
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI Bot: দুঃখিত, আমি উত্তর দিতে পারছি না।";
    }
}
