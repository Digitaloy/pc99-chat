<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Gemini AI Chat</h1>

        <div id="chat-box" class="border rounded p-4 h-96 overflow-y-auto bg-gray-100 mb-4">
            @foreach ($messages as $msg)
                <div class="{{ $msg->is_bot ? 'text-left text-green-700' : 'text-right text-blue-700' }} mb-2">
                    <strong>{{ $msg->is_bot ? 'AI Bot' : $msg->user->name }}:</strong>
                    <p>{{ $msg->message }}</p>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('chat.send') }}" class="flex space-x-2">
            @csrf
            <input type="text" name="message" placeholder="Type your message..." required
                class="flex-grow border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Send</button>
        </form>
    </div>
</x-app-layout>
