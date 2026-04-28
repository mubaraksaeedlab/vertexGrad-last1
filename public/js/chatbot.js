// Chatbot JavaScript
class Chatbot {
    constructor() {
        this.sessionId = this.generateSessionId();
        this.currentLanguage = this.detectLanguage();
        this.messages = [];
        this.lastConversationId = null;
        this.init();
    }
    
    init() {
        // تحميل الاقتراحات
        this.loadSuggestions();
        
        // استماع للأحداث
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('suggestion-btn')) {
                this.sendMessage(e.target.textContent);
            }
        });
    }
    
    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    detectLanguage() {
        // كشف لغة المتصفح
        const lang = navigator.language || navigator.userLanguage;
        return lang.startsWith('ar') ? 'ar' : 'en';
    }
    
    async loadSuggestions() {
        try {
            const response = await fetch('/api/chatbot/suggestions?lang=' + this.currentLanguage);
            const data = await response.json();
            
            if (data.success) {
                this.displaySuggestions(data.suggestions);
            }
        } catch (error) {
            console.error('Failed to load suggestions:', error);
        }
    }
    
    displaySuggestions(suggestions) {
        const container = document.getElementById('chatSuggestions');
        if (!container) return;
        
        container.innerHTML = '';
        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'suggestion-btn';
            btn.textContent = suggestion;
            btn.onclick = () => this.sendMessage(suggestion);
            container.appendChild(btn);
        });
    }
    
    async sendMessage(message) {
        if (!message.trim()) return;
        
        // عرض رسالة المستخدم
        this.addMessage(message, 'user');
        
        // مسح الإدخال
        const input = document.getElementById('chatInput');
        if (input) input.value = '';
        
        // مسح الاقتراحات
        document.getElementById('chatSuggestions').innerHTML = '';
        
        // عرض مؤشر الكتابة
        this.showTypingIndicator();
        
        try {
            // الحصول على CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // إرسال للخادم
            const response = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId
                })
            });
            
            const data = await response.json();
            
            // إخفاء مؤشر الكتابة
            this.hideTypingIndicator();
            
            if (data.success) {
                // عرض رد البوت
                this.addMessage(data.message, 'bot');
                
                // حفظ معرف المحادثة للتقييم
                if (data.conversation_id) {
                    this.lastConversationId = data.conversation_id;
                }
                
                // عرض الإجراءات
                if (data.actions && data.actions.length > 0) {
                    this.displayActions(data.actions);
                }
                
                // عرض اقتراحات متابعة
                if (data.suggestions && data.suggestions.length > 0) {
                    this.displaySuggestions(data.suggestions);
                }
                
                // طلب تقييم بعد 3 ثواني
                setTimeout(() => {
                    this.requestFeedback();
                }, 3000);
            }
            
        } catch (error) {
            console.error('Failed to send message:', error);
            this.hideTypingIndicator();
            this.addMessage(
                this.currentLanguage === 'ar' 
                    ? 'عذراً، حدث خطأ في الاتصال' 
                    : 'Sorry, connection error',
                'bot'
            );
        }
    }
    
    addMessage(text, sender) {
        const messagesDiv = document.getElementById('chatMessages');
        if (!messagesDiv) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.textContent = text;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = new Date().toLocaleTimeString(
            this.currentLanguage === 'ar' ? 'ar-SA' : 'en-US',
            { hour: '2-digit', minute: '2-digit' }
        );
        
        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);
        messagesDiv.appendChild(messageDiv);
        
        // تمرير لأسفل
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    
    showTypingIndicator() {
        const messagesDiv = document.getElementById('chatMessages');
        if (!messagesDiv) return;
        
        const indicator = document.createElement('div');
        indicator.className = 'message bot typing-indicator';
        indicator.id = 'typingIndicator';
        indicator.innerHTML = '<div class="message-content">...</div>';
        messagesDiv.appendChild(indicator);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    
    hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }
    
    displayActions(actions) {
        const container = document.getElementById('chatSuggestions');
        if (!container) return;
        
        container.innerHTML = '';
        actions.forEach(action => {
            const text = action.text[this.currentLanguage] || action.text.en;
            const btn = document.createElement('button');
            btn.className = 'suggestion-btn action-btn';
            btn.textContent = text;
            btn.onclick = () => window.location.href = action.url;
            container.appendChild(btn);
        });
    }
    
    requestFeedback() {
        if (!this.lastConversationId) return;
        
        // إضافة طلب تقييم بسيط
        const messagesDiv = document.getElementById('chatMessages');
        const feedbackDiv = document.createElement('div');
        feedbackDiv.className = 'feedback-request';
        feedbackDiv.innerHTML = `
            <div class="feedback-text">
                ${this.currentLanguage === 'ar' ? 'هل كان هذا الرد مفيداً؟' : 'Was this response helpful?'}
            </div>
            <div class="feedback-buttons">
                <button onclick="chatbot.sendFeedback(5)">👍</button>
                <button onclick="chatbot.sendFeedback(3)">😐</button>
                <button onclick="chatbot.sendFeedback(1)">👎</button>
            </div>
        `;
        messagesDiv.appendChild(feedbackDiv);
    }
    
    async sendFeedback(rating) {
        if (!this.lastConversationId) return;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            await fetch('/api/chatbot/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    conversation_id: this.lastConversationId,
                    rating: rating
                })
            });
            
            // إزالة طلب التقييم
            const feedback = document.querySelector('.feedback-request');
            if (feedback) feedback.remove();
            
        } catch (error) {
            console.error('Failed to send feedback:', error);
        }
    }
}

// تهيئة الشات
let chatbot;
document.addEventListener('DOMContentLoaded', () => {
    chatbot = new Chatbot();
});

// دوال عامة
function toggleChatbot() {
    const widget = document.getElementById('chatbotWidget');
    if (widget) {
        widget.style.display = widget.style.display === 'none' ? 'flex' : 'none';
        
        // تحديث الإشعار
        const notification = document.getElementById('chatNotification');
        if (notification) notification.style.display = 'none';
    }
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    if (input && input.value.trim()) {
        chatbot.sendMessage(input.value.trim());
    }
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// تصدير للاستخدام العام
window.chatbot = chatbot;
window.toggleChatbot = toggleChatbot;
window.sendMessage = sendMessage;
window.handleKeyPress = handleKeyPress;