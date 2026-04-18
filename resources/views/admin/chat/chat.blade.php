@extends('admin.layout')

@section('title', 'Chat with ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-chat-dots-fill me-2"></i>Chat with {{ $customer->name }}
        </h1>
        <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Conversations
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($customer->avatar && file_exists(public_path('storage/' . $customer->avatar)))
                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                 alt="{{ $customer->name }}" 
                                 class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;"
                                 onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 alt="{{ $customer->name }}" 
                                 class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="m-0 font-weight-bold text-primary">{{ $customer->name }}</h6>
                            <small class="text-muted">{{ $customer->email }}</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" id="refresh-messages">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
                <div class="card-body p-0">
                    <!-- Chat Messages -->
                    <div id="chat-messages" class="chat-messages p-4" style="height: 500px; overflow-y: auto;">
                        @if($messages->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #e3e6f0;"></i>
                                <p class="text-muted mt-3">No messages yet. Start the conversation!</p>
                            </div>
                        @else
                            @foreach($messages as $message)
                                <div class="message mb-3 {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                                    <div class="d-flex {{ $message->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div class="message-bubble {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-white border' }} rounded-3 px-3 py-2" style="max-width: 70%; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <p class="mb-1">{{ $message->message }}</p>
                                            <small class="{{ $message->sender_id === Auth::id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.75rem;">
                                                {{ $message->created_at->format('H:i') }}
                                                @if($message->sender_id === Auth::id() && $message->is_read)
                                                    <i class="bi bi-check-all ms-1"></i>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Message Input -->
                    <div class="chat-input p-3 border-top bg-white">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Test JavaScript:</small>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="testJS()">Test JS</button>
                        </div>
                        <form id="message-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="message-input" class="form-control" placeholder="Type your message..." maxlength="1000" required>
                                <button type="submit" class="btn btn-primary" id="send-btn">
                                    <i class="bi bi-send-fill"></i> Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-bubble {
    word-wrap: break-word;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-messages {
    background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
}
</style>

<script>
console.log('Admin chat page loading...'); // Debug log

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing chat...'); // Debug log
    
    const chatMessages = document.getElementById('chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const customerId = {{ $customer->id }};
    
    console.log('Elements found:', {
        chatMessages: !!chatMessages,
        messageForm: !!messageForm,
        messageInput: !!messageInput,
        sendBtn: !!sendBtn,
        customerId: customerId
    }); // Debug log
    
    // Test function
    window.testJS = function() {
        console.log('Test JS function called');
        alert('JavaScript is working! Current input value: ' + messageInput.value);
    };
    
    // Scroll to bottom on load
    scrollToBottom();
    
    // Handle message submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted'); // Debug log
        
        const message = messageInput.value.trim();
        console.log('Message:', message); // Debug log
        
        if (!message) {
            console.log('Empty message, returning'); // Debug log
            return;
        }
        
        // Disable send button
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
        
        console.log('Sending message to:', `{{ route("admin.chat.send", $customer->id) }}`); // Debug log
        
        // Send message via AJAX
        fetch(`{{ route("admin.chat.send", $customer->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => {
            console.log('Response received:', response); // Debug log
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log
            if (data.success) {
                messageInput.value = '';
                // Add message to chat immediately for better UX
                addMessageToChat(data.message, true);
                scrollToBottom();
            } else {
                console.error('Error sending message:', data.error || 'Unknown error');
                alert('Error sending message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending message. Please try again.');
        })
        .finally(() => {
            // Re-enable send button
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-send-fill"></i> Send';
            messageInput.focus();
        });
    });
    
    // Auto-refresh messages every 3 seconds
    setInterval(function() {
        fetchMessages();
    }, 3000);
    
    // Refresh button
    document.getElementById('refresh-messages').addEventListener('click', function() {
        fetchMessages();
    });
    
    function fetchMessages() {
        fetch(`{{ route("admin.chat.messages", $customer->id) }}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    updateChatMessages(data.messages);
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }
    
    function addMessageToChat(message, isSent = false) {
        const messageHtml = `
            <div class="message mb-3 ${isSent ? 'sent' : 'received'}" data-message-id="${message.id}">
                <div class="d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'}">
                    <div class="message-bubble ${isSent ? 'bg-primary text-white' : 'bg-white border'} rounded-3 px-3 py-2" style="max-width: 70%; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <p class="mb-1">${message.message}</p>
                        <small class="${isSent ? 'text-white-50' : 'text-muted'}" style="font-size: 0.75rem;">
                            ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                        </small>
                    </div>
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }
    
    function updateChatMessages(messages) {
        const existingMessageIds = Array.from(chatMessages.querySelectorAll('.message[data-message-id]'))
            .map(el => el.dataset.messageId)
            .filter(id => id !== undefined);
        
        messages.forEach(message => {
            if (!existingMessageIds.includes(message.id.toString())) {
                const isSent = message.sender_id === {{ Auth::id() }};
                addMessageToChat(message, isSent);
            }
        });
        
        scrollToBottom();
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Focus on input field
    messageInput.focus();
});
</script>
@endsection
