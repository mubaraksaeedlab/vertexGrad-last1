# -*- coding: utf-8 -*-
from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
import json
import time
from datetime import datetime

app = Flask(__name__)
CORS(app)

print("="*60)
print("🇾🇪 شات بوت المنصة اليمنية - النسخة المحسّنة")
print("="*60)

# ========== قاعدة معرفة شاملة ==========
knowledge_base = {
    
    # ===== المعلومات الأساسية =====
    "منصة": {
        "keywords": ["منصة", "المنصة", "موقع", "تطبيق", "platform"],
        "answer": "🌟 **منصتنا يمنية:** منصة يمنية متكاملة لريادة الأعمال والاستثمار، تأسست عام 2026 في صنعاء.\n\n**خدماتنا:**\n✅ تمويل المشاريع اليمنية\n✅ استشارات متخصصة\n✅ ورش عمل ودورات\n✅ ربط المستثمرين بأصحاب المشاريع\n✅ دعم فني 24 ساعة\n\n**للتواصل:**\n📧 info@yemenplatform.com\n📞 777123456\n🌐 www.yemenplatform.com"
    },
    
    "عن": {
        "keywords": ["عن", "من أنتم", "من نحن", "عن المنصة", "عنكم", "about"],
        "answer": "🇾🇪 **من نحن:**\n\nمنصة يمنية رائدة في مجال ريادة الأعمال والاستثمار، تأسست عام 2022. نهدف إلى تمكين الشباب اليمني من تحويل أفكارهم إلى مشاريع ناجحة، وربطهم بالمستثمرين.\n\n**رؤيتنا:** أن نكون المنصة الأولى في اليمن والوطن العربي لتمويل المشاريع الناشئة.\n\n**رسالتنا:** خلق بيئة محفزة للابتكار وريادة الأعمال في اليمن."
    },
    
    "تواصل": {
        "keywords": ["تواصل", "اتصال", "رقم", "جوال", "واتس", "بريد", "إيميل", "contact", "phone", "email"],
        "answer": "📞 **معلومات التواصل:**\n\n**الدعم الفني:**\n📧 support@yemenplatform.com\n📞 777123456\n💬 واتساب: +967777123456\n\n**الاستفسارات العامة:**\n📧 info@yemenplatform.com\n\n**الاستثمار:**\n📧 investors@yemenplatform.com\n\n**الوظائف:**\n📧 hr@yemenplatform.com\n\n**أوقات العمل:** 24 ساعة طوال الأسبوع"
    },
    
    # ===== المشاريع =====
    "مشروع": {
        "keywords": ["مشروع", "تقديم", "أضيف", "اضافة", "فكرة", "project", "idea"],
        "answer": "📝 **كيف تقدم مشروعك في المنصة اليمنية:**\n\n**الخطوات:**\n1️⃣ سجل دخولك إلى حسابك\n2️⃣ اضغط على 'إضافة مشروع'\n3️⃣ املأ البيانات:\n   • اسم المشروع\n   • وصف المشروع\n   • المبلغ المطلوب\n   • الفئة (تقني، تجاري، زراعي)\n4️⃣ ارفع المستندات المطلوبة:\n   • دراسة جدوى\n   • صور المشروع\n   • خطة العمل\n5️⃣ انتظر المراجعة (24 ساعة)\n\n**شروط القبول:**\n✅ فكرة مبتكرة\n✅ دراسة جدوى واضحة\n✅ فريق عمل مؤهل\n\nبعد الموافقة، مشروعك راح ينعرض للمستثمرين!"
    },
    
    "استثمار": {
        "keywords": ["استثمار", "استثمر", "invest", "investment"],
        "answer": "💰 **الاستثمار في المنصة اليمنية:**\n\n**أقل مبلغ:** 50,000 ريال يمني\n**نسبة الربح:** 10% - 25% سنوياً\n**عدد المستثمرين:** +5,000 مستثمر\n**إجمالي الاستثمارات:** 2.5 مليار ريال\n\n**خطوات الاستثمار:**\n1️⃣ سجل كمستثمر\n2️⃣ وثق حسابك\n3️⃣ تصفح المشاريع المتاحة\n4️⃣ اختر المشروع المناسب\n5️⃣ حدد المبلغ\n6️⃣ تابع أرباحك شهرياً"
    },
    
    "أرباح": {
        "keywords": ["أرباح", "ربح", "عائد", "نسبة", "profit", "return"],
        "answer": "📈 **الأرباح والعوائد:**\n\n**نسبة الربح المتوقعة:** 10-25% سنوياً\n**توزيع الأرباح:** شهرياً\n**إجمالي الأرباح المصروفة:** 500 مليون ريال\n**أعلى ربح حققته مشروع:** 40%\n\n**طرق سحب الأرباح:**\n• تحويل بنكي\n• فواتير\n• محفظة المنصة"
    },
    
    "تسجيل": {
        "keywords": ["تسجيل", "دخول", "login", "register", "حساب"],
        "answer": "🔐 **كيف تسجل دخولك للمنصة اليمنية:**\n\n**للمستخدمين الحاليين:**\n1️⃣ اذهب إلى yemenplatform.com\n2️⃣ اضغط 'تسجيل دخول'\n3️⃣ أدخل بريدك الإلكتروني\n4️⃣ أدخل كلمة المرور\n5️⃣ اضغط 'دخول'\n\n**للمستخدمين الجدد:**\n1️⃣ اضغط 'تسجيل جديد'\n2️⃣ اختر نوع الحساب\n3️⃣ أدخل البيانات\n4️⃣ وثق حسابك"
    },
    
    "معلق": {
        "keywords": ["معلق", "لم يفعل", "ما يفعل", "متى يفعل", "pending", "activation"],
        "answer": "⏳ **حسابك معلق؟**\n\nمدة التفعيل المعتادة: 24 ساعة كحد أقصى\n\n**إذا تعدت 24 ساعة:**\n📧 أرسل للدعم: support@yemenplatform.com\n📞 اتصل: 777123456\n💬 واتساب: +967777123456"
    },
    
    "مشكلة": {
        "keywords": ["مشكلة", "عطل", "خطأ", "لا يعمل", "ما يشتغل", "problem", "error"],
        "answer": "🛠️ **مشاكل وحلول:**\n\n**الموقع لا يعمل؟**\n1️⃣ حدث الصفحة (F5)\n2️⃣ امسح الكاش (Ctrl+Shift+Del)\n3️⃣ جرب متصفح آخر\n4️⃣ تأكد من اتصال الإنترنت\n\n**تسجيل الدخول لا يعمل؟**\n1️⃣ تأكد من البريد وكلمة المرور\n2️⃣ استخدم 'نسيت كلمة المرور'\n\nإذا استمرت المشكلة:\n📧 support@yemenplatform.com\n📞 777123456"
    },
    
    "فعاليات": {
        "keywords": ["فعاليات", "events", "معرض", "مؤتمر", "ندوة"],
        "answer": "📅 **الفعاليات القادمة في اليمن:**\n\n🗓️ **15 مارس 2026**\nمعرض صنعاء لريادة الأعمال\n📍 صنعاء - مركز المعارض الدولي\n\n🗓️ **22 مارس 2026**\nورشة 'كيف تبدأ مشروعك'\n📍 عدن - قاعة الاجتماعات\n\n🗓️ **5 أبريل 2026**\nندوة الاستثمار الآمن\n📍 أونلاين (Zoom)"
    },
    
    "تقرير": {
        "keywords": ["تقرير", "report", "إحصائيات", "statistics"],
        "answer": "📊 **التقارير المتاحة:**\n\n📄 **تقرير المشاريع الشهري**\nعدد المشاريع، المشاريع الممولة، نسبة النجاح\n\n📈 **إحصائيات الاستثمارات**\nإجمالي الاستثمارات، توزيع الاستثمارات، أكبر المستثمرين\n\n📉 **أداء المشاريع**\nنسب النجاح، العوائد المتوقعة، مؤشرات الأداء"
    },
    
    "وظيفة": {
        "keywords": ["وظيفة", "وظائف", "توظيف", "job", "career"],
        "answer": "💼 **فرص العمل في المنصة اليمنية:**\n\n**الوظائف المتاحة حالياً:**\n\n1️⃣ **مطور ويب** - خبرة في Python/Flask\n2️⃣ **مسوق رقمي** - خبرة في السوشل ميديا\n3️⃣ **محلل استثماري** - خلفية مالية\n4️⃣ **مصمم جرافيك** - خبرة في Photoshop\n5️⃣ **موظف دعم فني** - مهارات تواصل عالية\n\n**طريقة التقديم:**\nأرسل سيرتك الذاتية إلى: hr@yemenplatform.com"
    },
    
    "رسوم": {
        "keywords": ["رسوم", "fees", "تكلفة", "سعر"],
        "answer": "💰 **رسوم المنصة:**\n\n✅ التسجيل: مجاني 100%\n✅ إضافة مشروع: مجاني\n✅ الاستثمار: 2% من الأرباح فقط\n✅ سحب الأرباح: مجاني\n✅ الدعم الفني: مجاني 24 ساعة\n\n**لا توجد رسوم خفية!**"
    },
    
    "مساعدة": {
        "keywords": ["مساعدة", "help", "استفسار"],
        "answer": "🆘 **مركز المساعدة:**\n\n**كيف نقدر نساعدك؟**\n\n📌 **مواضيع المساعدة:**\n• المشاريع - تقديم وإدارة المشاريع\n• الاستثمار - كيف تستثمر وتتابع أرباحك\n• الحسابات - تسجيل دخول وتوثيق\n• الدعم الفني - مشاكل تقنية\n• الفعاليات - أحداث قادمة\n• التقارير - إحصائيات وبيانات\n\n**طرق التواصل:**\n📧 البريد: support@yemenplatform.com\n📞 الهاتف: 777123456\n💬 واتساب: +967777123456"
    }
}

# ========== دالة البحث عن الإجابة ==========
def find_answer(question):
    question = question.lower()
    
    # البحث في قاعدة المعرفة
    for key, data in knowledge_base.items():
        for keyword in data["keywords"]:
            if keyword in question:
                return data["answer"]
    
    # إذا ما لقى شيء، رد افتراضي
    return """عذراً، لم أتمكن من الإجابة على سؤالك. هل يمكنك توضيح أكثر؟ 

**يمكنك السؤال عن:** 
📌 المشاريع - كيف تقدم مشروعك
💰 الاستثمار - كيف تستثمر وأرباحك
👤 الحسابات - تسجيل دخول وتوثيق
🛠️ الدعم الفني - مشاكل تقنية
📅 الفعاليات - معارض وندوات
📊 التقارير - إحصائيات وأرقام
💼 الوظائف - فرص عمل

أو تواصل معنا مباشرة:
📞 777123456 | 📧 support@yemenplatform.com"""

# ========== واجهة المستخدم المحسّنة (شات بوب أب) ==========
HTML_TEMPLATE = '''
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🇾🇪 شات بوت المنصة اليمنية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* زر فتح الشات */
        .chat-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
            font-size: 28px;
            z-index: 9999;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 99, 235, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
            }
        }

        .chat-toggle-btn:hover {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
        }

        /* نافذة الشات */
        .chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 380px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            z-index: 9999;
            overflow: hidden;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* رأس الشات */
        .chat-header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-title i {
            font-size: 28px;
        }

        .chat-title h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .chat-title p {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        /* معلومات الشات */
        .chat-info {
            background: #f8fafc;
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge {
            background: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            color: #2563eb;
            border: 1px solid #2563eb;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* منطقة الرسائل */
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            display: flex;
            animation: messageSlide 0.3s ease;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            justify-content: flex-end;
        }

        .bot-message {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
        }

        .user-message .message-content {
            background: #2563eb;
            color: white;
            border-bottom-right-radius: 5px;
        }

        .bot-message .message-content {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .message-time {
            font-size: 10px;
            margin-top: 5px;
            opacity: 0.7;
        }

        .user-message .message-time {
            text-align: left;
            color: #94a3b8;
        }

        .bot-message .message-time {
            text-align: right;
            color: #94a3b8;
        }

        /* اقتراحات سريعة */
        .suggestions {
            padding: 15px 20px;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .suggestions-title {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 10px;
        }

        .suggestions-grid {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: thin;
        }

        .suggestions-grid::-webkit-scrollbar {
            height: 4px;
        }

        .suggestions-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .suggestion-btn {
            background: #f1f5f9;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            color: #1e293b;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .suggestion-btn:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            transform: translateY(-2px);
        }

        /* منطقة الإدخال */
        .chat-input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 10px;
        }

        .chat-input-area input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }

        .chat-input-area input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .chat-input-area button {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .chat-input-area button:hover {
            background: #1e40af;
            transform: scale(1.1) rotate(15deg);
        }

        /* تذييل */
        .chat-footer {
            padding: 12px;
            text-align: center;
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            border-top: 1px solid #e2e8f0;
        }

        /* مؤشر الكتابة */
        .typing-indicator {
            display: flex;
            gap: 5px;
            padding: 10px;
            background: white;
            border-radius: 15px;
            width: fit-content;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typing 1s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }

        /* إشعار */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid white;
        }

        /* تنسيق الروابط */
        .message-content a {
            color: inherit;
            text-decoration: underline;
        }

        .message-content a:hover {
            opacity: 0.8;
        }

        /* تنسيق القوائم */
        .message-content ul, 
        .message-content ol {
            margin: 5px 0 5px 20px;
        }

        .message-content li {
            margin: 3px 0;
        }

        /* تنسيق العناوين */
        .message-content h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: inherit;
        }

        .message-content h4 {
            font-size: 14px;
            margin: 10px 0 5px;
            color: inherit;
        }

        /* التجاوب مع الشاشات الصغيرة */
        @media (max-width: 480px) {
            .chat-widget {
                width: 100%;
                height: 100%;
                bottom: 0;
                right: 0;
                border-radius: 0;
            }
            
            .chat-toggle-btn {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- زر فتح الشات -->
    <button class="chat-toggle-btn" onclick="toggleChat()">
        💬
        <span class="notification-badge" id="notificationBadge">1</span>
    </button>

    <!-- نافذة الشات -->
    <div class="chat-widget" id="chatWidget">
        <div class="chat-header">
            <div class="chat-title">
                <i>🤖</i>
                <div>
                    <h3>شات بوت المنصة اليمنية</h3>
                    <p>نحن هنا لمساعدتك 24/7</p>
                </div>
            </div>
            <button class="close-btn" onclick="toggleChat()">
                ✕
            </button>
        </div>

        <div class="chat-info">
            <div class="info-badges">
                <span class="badge">📚 50+ موضوع</span>
                <span class="badge">⏰ 24/7 دعم</span>
                <span class="badge">🎯 100% دقة</span>
                <span class="badge">🇾🇪 يمني 100%</span>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="bot-message">
                <div class="message-content">
                    👋 مرحباً بك في المنصة اليمنية! 
                    
أنا شات بوت ذكي جاهز للإجابة عن كل أسئلتك. ماذا تريد أن تعرف؟
                    
يمكنك السؤال عن:
📌 المشاريع والاستثمار
🔐 الحسابات والتسجيل
🛠️ الدعم الفني
📅 الفعاليات القادمة
📊 التقارير والإحصائيات
💼 فرص العمل
                    <div class="message-time">تم التسجيل</div>
                </div>
            </div>
        </div>

        <div class="suggestions">
            <div class="suggestions-title">🔍 اقتراحات سريعة:</div>
            <div class="suggestions-grid" id="suggestionsGrid">
                <button class="suggestion-btn" onclick="useSuggestion('كيف أضيف مشروع')">📝 إضافة مشروع</button>
                <button class="suggestion-btn" onclick="useSuggestion('كيف أستثمر')">💰 استثمار</button>
                <button class="suggestion-btn" onclick="useSuggestion('كم نسبة الربح')">📈 أرباح</button>
                <button class="suggestion-btn" onclick="useSuggestion('مشكلة في الموقع')">🛠️ مشكلة تقنية</button>
                <button class="suggestion-btn" onclick="useSuggestion('ما هي رسوم المنصة')">💰 الرسوم</button>
                <button class="suggestion-btn" onclick="useSuggestion('وش فيه فعاليات')">📅 فعاليات</button>
                <button class="suggestion-btn" onclick="useSuggestion('أريد وظيفة')">💼 وظائف</button>
                <button class="suggestion-btn" onclick="useSuggestion('كيف أوثق حسابي')">🔐 توثيق</button>
            </div>
        </div>

        <div class="chat-input-area">
            <input type="text" id="messageInput" placeholder="اكتب سؤالك هنا..." onkeypress="if(event.key=='Enter') sendMessage()">
            <button onclick="sendMessage()">
                ➤
            </button>
        </div>

        <div class="chat-footer">
            🇾🇪 منصة يمنية - جميع الحقوق محفوظة © 2026
        </div>
    </div>

    <script>
        let isChatOpen = false;
        let messageCount = 1;

        // فتح وغلق الشات
        function toggleChat() {
            const widget = document.getElementById('chatWidget');
            const badge = document.getElementById('notificationBadge');
            
            if (isChatOpen) {
                widget.style.display = 'none';
                isChatOpen = false;
            } else {
                widget.style.display = 'flex';
                isChatOpen = true;
                badge.style.display = 'none';
            }
        }

        // استخدام الاقتراحات
        function useSuggestion(text) {
            document.getElementById('messageInput').value = text;
            sendMessage();
        }

        // إرسال الرسالة
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;

            // عرض رسالة المستخدم
            addMessage(message, 'user');
            input.value = '';

            // عرض مؤشر الكتابة
            const typingId = showTyping();

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                // إزالة مؤشر الكتابة
                removeTyping(typingId);

                if (data.success) {
                    addMessage(data.answer, 'bot');
                } else {
                    addMessage('عذراً، حدث خطأ. يرجى المحاولة مرة أخرى.', 'bot');
                }
            } catch (error) {
                removeTyping(typingId);
                addMessage('❌ عذراً، حدث خطأ في الاتصال. تأكد من اتصالك بالإنترنت.', 'bot');
            }
        }

        // إضافة رسالة
        function addMessage(text, sender) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            
            const time = new Date().toLocaleTimeString('ar-EG', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            messageDiv.className = sender === 'user' ? 'user-message' : 'bot-message';
            
            // تنسيق النص
            let formattedText = text
                .replace(/\\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            messageDiv.innerHTML = `
                <div class="message-content">
                    ${formattedText}
                    <div class="message-time">${time}</div>
                </div>
            `;

            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // إظهار مؤشر الكتابة
        function showTyping() {
            const messagesDiv = document.getElementById('chatMessages');
            const typingDiv = document.createElement('div');
            const id = 'typing-' + Date.now();
            
            typingDiv.id = id;
            typingDiv.className = 'bot-message';
            typingDiv.innerHTML = `
                <div class="message-content">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;

            messagesDiv.appendChild(typingDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            
            return id;
        }

        // إزالة مؤشر الكتابة
        function removeTyping(id) {
            const typing = document.getElementById(id);
            if (typing) {
                typing.remove();
            }
        }

        // إظهار إشعار بعد 3 ثواني
        setTimeout(() => {
            const badge = document.getElementById('notificationBadge');
            if (!isChatOpen) {
                badge.style.display = 'flex';
            }
        }, 3000);

        // جلب اقتراحات إضافية (اختياري)
        async function loadMoreSuggestions() {
            try {
                const response = await fetch('/suggestions');
                const data = await response.json();
                if (data.suggestions) {
                    const grid = document.getElementById('suggestionsGrid');
                    data.suggestions.forEach(suggestion => {
                        const btn = document.createElement('button');
                        btn.className = 'suggestion-btn';
                        btn.onclick = () => useSuggestion(suggestion.text);
                        btn.textContent = suggestion.icon + ' ' + suggestion.text;
                        grid.appendChild(btn);
                    });
                }
            } catch (error) {
                console.log('لا يمكن تحميل اقتراحات إضافية');
            }
        }

        // تحميل اقتراحات إضافية
        // loadMoreSuggestions();
    </script>
</body>
</html>
'''

# ========== واجهة برمجة التطبيقات ==========
@app.route('/')
def home():
    return render_template_string(HTML_TEMPLATE)

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        question = data.get('message', '')
        
        if not question:
            return jsonify({"error": "لا يوجد سؤال"}), 400
        
        answer = find_answer(question)
        
        return jsonify({
            "success": True,
            "answer": answer,
            "question": question,
            "timestamp": time.time()
        })
        
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/health')
def health():
    return jsonify({
        "status": "healthy",
        "topics": len(knowledge_base),
        "version": "2.0.0"
    })

@app.route('/suggestions')
def get_suggestions():
    """جلب اقتراحات إضافية"""
    suggestions = [
        {"text": "كيف أضيف مشروع", "icon": "📝"},
        {"text": "كم نسبة الربح", "icon": "📈"},
        {"text": "مشكلة في الدفع", "icon": "💳"},
        {"text": "توثيق الحساب", "icon": "🔐"},
        {"text": "فعاليات قادمة", "icon": "📅"},
        {"text": "فرص عمل", "icon": "💼"},
        {"text": "رسوم المنصة", "icon": "💰"},
        {"text": "تواصل معنا", "icon": "📞"}
    ]
    return jsonify({"suggestions": suggestions})

if __name__ == '__main__':
    print("\n" + "="*60)
    print("🚀 شات بوت المنصة اليمنية - النسخة المحسّنة")
    print("📚 قاعدة معرفة: {} موضوع".format(len(knowledge_base)))
    print("📍 الرابط: http://localhost:5000")
    print("="*60 + "\n")
    
    app.run(
        host='0.0.0.0', 
        port=5000, 
        debug=True,
        threaded=True
    )