@extends('admin.layout')

@section('title', 'Customer Chat')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-chat-dots-fill me-2"></i>Customer Chat
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-danger me-2" id="total-unread-count">0</span>
            <small class="text-muted">Unread Messages</small>
        </div>
    </div>

    <div class="row">
        <!-- Conversations List -->
        <div class="col-lg-4 col-xl-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conversations</h6>
                </div>
                <div class="card-body p-0">
                    <div id="conversations-list" style="height: 600px; overflow-y: auto;">
                        @if($conversations->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #e3e6f0;"></i>
                                <p class="text-muted mt-3">No conversations yet</p>
                            </div>
                        @else
                            @foreach($conversations as $userId => $conversation)
                                <div class="conversation-item d-flex align-items-center p-3 text-decoration-none border-bottom hover-bg-light cursor-pointer"
                                     data-user-id="{{ $userId }}" onclick="loadChat({{ $userId }})">
                                    <div class="flex-shrink-0">
                                        @if($conversation['user']->avatar && file_exists(public_path('storage/' . $conversation['user']->avatar)))
                                            <img src="{{ asset('storage/' . $conversation['user']->avatar) }}" 
                                                 alt="{{ $conversation['user']->name }}" 
                                                 class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" 
                                                 alt="{{ $conversation['user']->name }}" 
                                                 class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-dark">{{ $conversation['user']->name }}</h6>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $conversation['last_message']->created_at->format('H:i') }}</small>
                                        </div>
                                        <p class="mb-0 text-truncate small text-muted">
                                            {{ Str::limit($conversation['last_message']->message, 25) }}
                                        </p>
                                    </div>
                                    @if($conversation['unread_count'] > 0)
                                        <span class="badge bg-danger ms-2">{{ $conversation['unread_count'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-lg-8 col-xl-9">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary" id="chat-header">
                        Select a conversation to start messaging
                    </h6>
                    <button class="btn btn-sm btn-outline-secondary" id="refresh-chat" style="display: none;">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="chat-container">
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots" style="font-size: 4rem; color: #e3e6f0;"></i>
                            <p class="text-muted mt-3">Choose a conversation from the left to start chatting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.conversation-item:hover {
    background-color: #f8f9fa !important;
    cursor: pointer;
}

.conversation-item.active {
    background-color: #e3f2fd !important;
    border-left: 4px solid #2196f3;
}

.cursor-pointer {
    cursor: pointer !important;
}

.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-messages {
    height: 400px !important;
    max-height: 400px !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
    padding: 1rem !important;
    border-bottom: 1px solid #e9ecef;
}

/* Custom scrollbar styling */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.chat-input {
    background: white !important;
    border-top: 3px solid #007bff !important;
    padding: 1.5rem !important;
    position: relative;
    z-index: 10;
    box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
    margin-top: auto;
}

.chat-input .form-control {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: #000000 !important;
    background-color: #ffffff !important;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.chat-input .form-control::placeholder {
    color: #6c757d !important;
}

.chat-input .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    color: #000000 !important;
    background-color: #ffffff !important;
}

.chat-input .btn-primary {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    background: #007bff;
    border: 2px solid #007bff;
    transition: all 0.15s ease-in-out;
}

.chat-input .btn-primary:hover {
    background: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
}

.chat-input .input-group {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-radius: 0.5rem;
    overflow: hidden;
}

.typing-indicator {
    display: none;
    padding: 8px 12px;
    background: #e9ecef;
    border-radius: 12px;
    margin: 8px 0;
    width: fit-content;
}

.typing-indicator span {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #6c757d;
    margin: 0 2px;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

/* Ensure chat container has proper fixed structure */
#chat-container {
    height: 600px !important;
    max-height: 600px !important;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Make sure messages area takes available space but respects fixed height */
.chat-messages {
    flex: 1;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    min-height: 400px !important;
    max-height: 400px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentUserId = null;
    let messageInterval = null;
    let conversationInterval = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Helper function to truncate text
    function truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
    
    // Load conversations periodically
    function loadConversations() {
        fetch('{{ route("admin.chat.conversations") }}')
            .then(response => response.json())
            .then(data => {
                updateConversationsList(data.conversations);
                updateTotalUnreadCount(data.conversations);
            })
            .catch(error => {
                console.error('Error loading conversations:', error);
            });
    }
    
    function updateConversationsList(conversations) {
        const listContainer = document.getElementById('conversations-list');
        
        if (conversations.length === 0) {
            listContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #e3e6f0;"></i>
                    <p class="text-muted mt-3">No conversations yet</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        conversations.forEach(conv => {
            const unreadBadge = conv.unread_count > 0 ? 
                `<span class="badge bg-danger ms-2">${conv.unread_count}</span>` : '';
            
            html += `
                <div class="conversation-item d-flex align-items-center p-3 text-decoration-none border-bottom ${currentUserId == conv.user.id ? 'active' : 'hover-bg-light'} cursor-pointer"
                     data-user-id="${conv.user.id}" onclick="loadChat(${conv.user.id})">
                    <div class="flex-shrink-0">
                        <img src="${conv.user.avatar || '{{ asset('images/default-avatar.png') }}'}" 
                             alt="${conv.user.name}" 
                             class="rounded-circle" 
                             style="width: 45px; height: 45px; object-fit: cover;"
                             onerror="this.src='{{ asset('images/default-avatar.png') }}';">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-dark">${conv.user.name}</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">${new Date(conv.last_message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                        <p class="mb-0 text-truncate small text-muted">${truncateText(conv.last_message.message, 25)}</p>
                    </div>
                    ${unreadBadge}
                </div>
            `;
        });
        
        listContainer.innerHTML = html;
    }
    
    function updateTotalUnreadCount(conversations) {
        const totalUnread = conversations.reduce((sum, conv) => sum + conv.unread_count, 0);
        const badge = document.getElementById('total-unread-count');
        badge.textContent = totalUnread;
        badge.style.display = totalUnread > 0 ? 'inline-block' : 'none';
    }
    
    function loadChat(userId) {
        console.log('Loading chat for user:', userId); // Debug log
        currentUserId = userId;
        
        // Update active state
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('active');
        });
        const activeItem = document.querySelector(`[data-user-id="${userId}"]`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
        
        console.log('Fetching messages from:', `{{ route('admin.chat.messages', ':user') }}`.replace(':user', userId)); // Debug log
        
        // Load chat messages
        fetch(`{{ route('admin.chat.messages', ':user') }}`.replace(':user', userId))
            .then(response => {
                console.log('Messages response:', response); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('Messages data:', data); // Debug log
                displayChat(data.messages, userId);
            })
            .catch(error => {
                console.error('Error loading chat:', error);
                alert('Error loading chat. Please check console for details.');
            });
        
        // Start message polling
        if (messageInterval) clearInterval(messageInterval);
        messageInterval = setInterval(() => {
            fetch(`{{ route('admin.chat.messages', ':user') }}`.replace(':user', userId))
                .then(response => response.json())
                .then(data => {
                    updateChatMessages(data.messages);
                })
                .catch(error => {
                    console.error('Error updating chat:', error);
                });
        }, 3000);
    }
    
    // Make loadChat globally accessible
    window.loadChat = loadChat;
    
    function displayChat(messages, userId) {
        console.log('Displaying chat for user:', userId, 'with', messages.length, 'messages'); // Debug log
        const container = document.getElementById('chat-container');
        const userName = document.querySelector(`[data-user-id="${userId}"] h6`).textContent;
        
        console.log('User name:', userName); // Debug log
        console.log('Container found:', !!container); // Debug log
        
        let messagesHtml = '';
        if (messages.length === 0) {
            console.log('No messages, showing empty state'); // Debug log
            messagesHtml = `
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #e3e6f0;"></i>
                    <p class="text-muted mt-3">No messages yet. Start the conversation!</p>
                </div>
            `;
        } else {
            console.log('Building messages HTML'); // Debug log
            messagesHtml = '<div class="chat-messages p-4">';
            messages.forEach(message => {
                const isSent = message.sender_id === {{ Auth::id() }};
                messagesHtml += `
                    <div class="message mb-3 ${isSent ? 'sent' : 'received'}" data-message-id="${message.id}">
                        <div class="d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'}">
                            <div class="message-bubble ${isSent ? 'bg-primary text-white' : 'bg-white border'} rounded-3 px-3 py-2" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <p class="mb-1">${message.message}</p>
                                <small class="${isSent ? 'text-white-50' : 'text-muted'}" style="font-size: 0.75rem;">
                                    ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            });
            messagesHtml += '</div>';
        }
        
        console.log('Adding message input form'); // Debug log
        // Add message input with enhanced visual design
        messagesHtml += `
            <div class="chat-input" style="background: #ffffff; border-top: 4px solid #007bff;">
                <div class="mb-3 text-center">
                    <h6 class="mb-2 text-primary">
                        <i class="bi bi-chat-square-dots-fill me-2"></i>Reply to ${userName}
                    </h6>
                    <small class="text-muted">Type your message and press Enter or click Send</small>
                </div>
                <form id="message-form">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <div class="input-group input-group-lg">
                        <input type="text" id="message-input" class="form-control" placeholder="Type your message here..." maxlength="1000" required>
                        <button type="submit" class="btn btn-primary" id="send-btn">
                            <i class="bi bi-send-fill me-2"></i>Send Message
                        </button>
                    </div>
                    <div class="mt-2 text-end">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>Press Enter to send, Shift+Enter for new line
                        </small>
                    </div>
                </form>
            </div>
        `;
        
        console.log('Updating container HTML'); // Debug log
        container.innerHTML = messagesHtml;
        
        document.getElementById('chat-header').innerHTML = `
            <i class="bi bi-person-circle me-2"></i>${userName}
        `;
        document.getElementById('refresh-chat').style.display = 'block';
        
        console.log('Adding form handler'); // Debug log
        // Add form handler
        document.getElementById('message-form').addEventListener('submit', sendMessage);
        
        // Scroll to bottom
        const chatMessages = container.querySelector('.chat-messages');
        if (chatMessages) {
            console.log('Scrolling to bottom'); // Debug log
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        console.log('Chat display completed'); // Debug log
    }
    
    function updateChatMessages(messages) {
        const chatMessages = document.querySelector('.chat-messages');
        if (!chatMessages) return;
        
        const existingMessageIds = Array.from(chatMessages.querySelectorAll('.message[data-message-id]'))
            .map(el => el.dataset.messageId)
            .filter(id => id !== undefined);
        
        messages.forEach(message => {
            if (!existingMessageIds.includes(message.id.toString())) {
                const isSent = message.sender_id === {{ Auth::id() }};
                const messageHtml = `
                    <div class="message mb-3 ${isSent ? 'sent' : 'received'}" data-message-id="${message.id}">
                        <div class="d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'}">
                            <div class="message-bubble ${isSent ? 'bg-primary text-white' : 'bg-white border'} rounded-3 px-3 py-2" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <p class="mb-1">${message.message}</p>
                                <small class="${isSent ? 'text-white-50' : 'text-muted'}" style="font-size: 0.75rem;">
                                    ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', messageHtml);
            }
        });
        
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function sendMessage(e) {
        e.preventDefault();
        
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        if (!message || !currentUserId) return;
        
        const sendBtn = document.getElementById('send-btn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
        
        fetch(`{{ route('admin.chat.send', ':user') }}`.replace(':user', currentUserId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                // The message will appear through the periodic update
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
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-send-fill"></i> Send';
            input.focus();
        });
    }
    
    // Refresh button
    document.getElementById('refresh-chat').addEventListener('click', function() {
        if (currentUserId) {
            loadChat(currentUserId);
        }
        loadConversations();
    });
    
    // Start polling
    conversationInterval = setInterval(loadConversations, 10000);
    loadConversations();
});
</script>
@endsection
