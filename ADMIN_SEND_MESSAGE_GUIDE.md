# Admin Send Message Feature - User Guide

## Overview
Admins can now send messages to specific customers directly from the Customers Management page. Messages are delivered as notifications that appear as popup alerts on the user's dashboard.

## How to Send a Message

### Step 1: Navigate to Customers Page
1. Login as admin
2. Go to **Admin Dashboard** → **Customers**
3. You'll see a list of all registered customers

### Step 2: Click Send Message Button
1. Find the customer you want to message
2. Click the **blue chat icon** (💬) button in the Actions column
3. A message composition dialog will appear

### Step 3: Compose Your Message
Fill in the following fields:

#### **Message Title** (Required)
- Short, descriptive title for your message
- Examples:
  - "Order Shipped"
  - "Payment Reminder"
  - "Special Offer"
  - "Account Update"

#### **Message Content** (Required)
- The main message body
- Up to 1000 characters
- Be clear and concise
- Examples:
  - "Your order #1234 has been shipped and will arrive in 2-3 days."
  - "We have a special 20% discount on all products this weekend!"
  - "Please update your payment method to continue your subscription."

#### **Message Type** (Required)
Choose the appropriate type based on your message:

| Type | Color | When to Use |
|------|-------|-------------|
| **Info** | Blue | General information, announcements, updates |
| **Success** | Green | Order confirmations, successful actions, positive news |
| **Warning** | Yellow | Important notices, reminders, attention needed |
| **Error** | Red | Payment failures, critical alerts, urgent issues |

#### **Link URL** (Optional)
- Add a relevant link where users can take action
- Examples:
  - `https://yoursite.com/orders/1234` - Link to specific order
  - `https://yoursite.com/shop` - Link to shop
  - `https://yoursite.com/profile` - Link to profile page

### Step 4: Send the Message
1. Click **"Send Message"** button
2. Wait for confirmation
3. You'll see a success message when sent

## What Happens After Sending?

### For the User:
1. **Immediate Popup Alert**
   - Next time they login to their dashboard, they'll see a popup alert
   - The popup shows your message title and content
   - They can mark it as read or close it

2. **Dashboard Notification**
   - Message appears in the "Pending Messages" section
   - Shows title, content, and timestamp
   - Remains until they mark it as read

3. **Alert Banner**
   - A banner shows "You have X unread message(s)"
   - Dismissible by the user

### For the Admin:
- Instant confirmation that message was sent
- Message is stored in the database
- User will see it on their next dashboard visit

## Message Examples

### Example 1: Order Shipped
```
Title: Your Order Has Been Shipped! 🚚
Message: Great news! Your order #1234 has been shipped and is on its way. 
         You can expect delivery within 2-3 business days.
Type: Success
URL: https://yoursite.com/orders/1234
```

### Example 2: Payment Reminder
```
Title: Payment Reminder
Message: Your payment for order #5678 is pending. Please complete the payment 
         to avoid order cancellation.
Type: Warning
URL: https://yoursite.com/orders/5678
```

### Example 3: Promotional Message
```
Title: Weekend Sale - 25% OFF! 🎉
Message: This weekend only! Get 25% off on all products. Don't miss out on 
         this amazing deal. Shop now!
Type: Info
URL: https://yoursite.com/shop
```

### Example 4: Account Issue
```
Title: Action Required: Update Payment Method
Message: We couldn't process your recent payment. Please update your payment 
         method to continue enjoying our services.
Type: Error
URL: https://yoursite.com/profile
```

## Best Practices

### ✅ DO:
- Keep messages clear and concise
- Use appropriate message types (info, success, warning, error)
- Include relevant links for user actions
- Personalize messages when possible
- Send timely notifications (order updates, payment reminders)
- Use friendly, professional language

### ❌ DON'T:
- Send spam or unnecessary messages
- Use ALL CAPS (seems like shouting)
- Send duplicate messages
- Use error type for non-critical messages
- Include sensitive information (passwords, full card numbers)
- Send messages too frequently

## Message Types Guide

### 📘 Info (Blue)
**Use for:**
- General announcements
- New features or products
- Company updates
- Helpful tips
- Newsletter-style content

**Example:**
"We've added new products to our collection. Check them out!"

### ✅ Success (Green)
**Use for:**
- Order confirmations
- Successful payments
- Account activations
- Completed actions
- Positive updates

**Example:**
"Your order has been successfully placed! Order #1234"

### ⚠️ Warning (Yellow)
**Use for:**
- Payment reminders
- Low stock alerts
- Expiring offers
- Account warnings
- Important notices

**Example:**
"Your special offer expires in 24 hours!"

### 🚫 Error (Red)
**Use for:**
- Payment failures
- Order cancellations
- Critical account issues
- Urgent actions needed
- System errors affecting user

**Example:**
"Payment failed. Please update your payment method."

## Technical Details

### Database Storage
- Messages are stored in the `notifications` table
- Each notification has a unique UUID
- Tracks read/unread status
- Stores timestamp of creation

### User Experience
- Notifications appear as popups on dashboard
- Users can mark as read individually or all at once
- Notifications remain until marked as read
- No limit on number of notifications

### Security
- Only admins can send messages
- CSRF protection on all requests
- Input validation on all fields
- XSS protection enabled

## Troubleshooting

### Message not appearing for user?
1. Check if user has logged in after you sent the message
2. Verify the user ID is correct
3. Check browser console for errors
4. Ensure user is logging into the correct account

### "Failed to send message" error?
1. Check your internet connection
2. Verify you're logged in as admin
3. Try refreshing the page
4. Check if the customer account exists

### User says they didn't receive message?
1. Ask them to refresh their dashboard
2. Check if they marked it as read already
3. Verify you sent to the correct customer
4. Check the notifications table in database

## Future Enhancements

Potential improvements:
- Bulk messaging to multiple customers
- Message templates for common scenarios
- Scheduled messages
- Message history and tracking
- Email integration
- SMS notifications
- Read receipts

---

**Need Help?** Contact your system administrator or refer to the main notification system documentation.
