// resources/js/chat-bot.js

// متغيرات الشات
let isOpen = false;
let sessionId = 'chat_' + Date.now();

// فتح/غلق الشات
window.toggleChat = function() {
    const widget = document.getElementById('chatWidget');
    const badge = document.getElementById('notificationBadge');
    
    if (isOpen) {
        widget.style.display = 'none';
        isOpen = false;
    } else {
        widget.style.display = 'flex';
        isOpen = true;
        badge.style.display = 'none';
    }
};

// استخدام الاقتراحات
window.useSuggestion = function(text) {
    document.getElementById('messageInput').value = text;
    sendMessage();
};

// إرسال الرسالة
window.sendMessage = async function() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // رسالة المستخدم
    addMessage(message, 'user');
    input.value = '';
    
    // مؤشر الكتابة
    const typingId = showTyping();
    
    try {
        const response = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ 
                message: message, 
                session_id: sessionId 
            })
        });
        
        const data = await response.json();
        removeTyping(typingId);
        
        if (data.success) {
            addMessage(data.answer, 'bot');
        } else {
            addMessage('⚠️ البوت غير متصل. الرجاء المحاولة لاحقاً.', 'bot');
        }
    } catch (error) {
        removeTyping(typingId);
        addMessage('⚠️ البوت غير متصل. الرجاء المحاولة لاحقاً.', 'bot');
    }
};

// إضافة رسالة
function addMessage(text, sender) {
    const messages = document.getElementById('chatMessages');
    const div = document.createElement('div');
    
    div.className = `message ${sender}`;
    div.innerHTML = `<div class="message-content">${text.replace(/\n/g, '<br>')}</div>`;
    
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

// مؤشر الكتابة
function showTyping() {
    const messages = document.getElementById('chatMessages');
    const id = 'typing_' + Date.now();
    const div = document.createElement('div');
    
    div.id = id;
    div.className = 'message bot';
    div.innerHTML = `
        <div class="message-content">
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    `;
    
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return id;
}

function removeTyping(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

// إشعار بعد 3 ثواني
setTimeout(() => {
    if (!isOpen) {
        const badge = document.getElementById('notificationBadge');
        if (badge) badge.style.display = 'flex';
    }
}, 3000);
// أضف هذي الدوال

// بعد إضافة رد البوت، أضف التقييم
const originalAddMessage = addMessage;
window.addMessage = function(text, sender) {
    originalAddMessage(text, sender);
    
    if (sender === 'bot') {
        setTimeout(() => {
            addRatingButtons();
        }, 500);
    }
};

function addRatingButtons() {
    // امسح أي تقييم سابق
    const oldRating = document.querySelector('.message-rating');
    if (oldRating) oldRating.remove();
    
    const messages = document.getElementById('chatMessages');
    const ratingDiv = document.createElement('div');
    ratingDiv.className = 'message-rating';
    ratingDiv.innerHTML = `
        <div style="display: flex; gap: 5px; justify-content: center; margin: 10px; padding: 10px; background: #f8f9fa; border-radius: 10px;">
            <span style="font-size: 12px; color: #666;">هل كان الرد مفيداً؟</span>
            <button onclick="rateChat(5)" style="border: none; background: none; cursor: pointer;">⭐⭐⭐⭐⭐</button>
            <button onclick="rateChat(4)" style="border: none; background: none; cursor: pointer;">⭐⭐⭐⭐</button>
            <button onclick="rateChat(3)" style="border: none; background: none; cursor: pointer;">⭐⭐⭐</button>
            <button onclick="rateChat(2)" style="border: none; background: none; cursor: pointer;">⭐⭐</button>
            <button onclick="rateChat(1)" style="border: none; background: none; cursor: pointer;">⭐</button>
        </div>
    `;
    messages.appendChild(ratingDiv);
}

window.rateChat = async function(rating) {
    try {
        await fetch('/chat/rate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ 
                rating: rating,
                session_id: sessionId 
            })
        });
        
        // امسح أزرار التقييم بعد التقييم
        const ratingDiv = document.querySelector('.message-rating');
        if (ratingDiv) ratingDiv.remove();
        
    } catch (error) {
        console.log('خطأ في تسجيل التقييم');
    }
};