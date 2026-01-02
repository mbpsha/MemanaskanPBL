# 📧 Email Not Sending - Troubleshooting Guide

## ✅ **What I Fixed**

### **Problem:** Email only sends on FIRST verification
The logic now checks if the payment was NOT verified before:
```php
// Store old status BEFORE updating
$oldStatus = $payment->payment_status;

// Update payment
$payment->update([...]);

// Only send email if changing FROM non-verified TO verified
if ($newStatus === 'verified' && $oldStatus !== 'verified') {
    // Send email
}
```

---

## 🔍 **Why Email Might Not Be Sending**

### **1. Payment Already Verified**
If you already verified this payment before, it won't send email again.

**Solution:** Find a NEW registration with status "uploaded" and verify it.

### **2. Email Not Configured**
Check your `.env` file for email settings.

**For Testing (Logs to file):**
```env
MAIL_MAILER=log
```
Emails will be saved to `storage/logs/laravel.log`

**For Real Emails (Gmail):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="WebRunning"
```

### **3. Barcode Package Not Installed**
```bash
composer require picqer/php-barcode-generator
```

---

## 🧪 **How to Test**

### **Step 1: Check Email Configuration**
```bash
# Check if .env has MAIL settings
cat .env | grep MAIL
```

### **Step 2: Use Log Driver for Testing**
In `.env`:
```env
MAIL_MAILER=log
```

### **Step 3: Clear Config Cache**
```bash
php artisan config:clear
php artisan cache:clear
```

### **Step 4: Find a NEW Registration**
1. Go to `/admin/payments`
2. Find registration with status "uploaded" (NOT "verified")
3. Change to "Verified"
4. Click save

### **Step 5: Check Logs**
```bash
tail -f storage/logs/laravel.log
```

Look for:
```
Payment approval email sent to: user@example.com
```

OR if error:
```
Failed to send payment approval email: [error message]
```

---

## 📝 **Email Flow**

```
Admin clicks "Verified"
    ↓
Check: Is old status NOT "verified"?
    ↓ YES
Store old status
    ↓
Update payment to "verified"
    ↓
Generate BIB number (if not exists)
    ↓
Refresh model
    ↓
Send email
    ↓
Log success/error
    ↓
Show success message
```

---

## 🔧 **Quick Fixes**

### **If using Gmail:**
1. Enable 2-Factor Authentication
2. Generate App Password
3. Use App Password in `.env`

### **If using log driver:**
Check `storage/logs/laravel.log` for the email content

### **If barcode missing:**
```bash
composer require picqer/php-barcode-generator
```

---

## 📧 **Test Email Manually**

Create a test route:
```php
Route::get('/test-email', function() {
    $registration = \App\Models\EventRegistration::first();
    \Mail::to('test@example.com')->send(new \App\Mail\PaymentApprovedMail($registration));
    return 'Email sent!';
});
```

Visit: `http://localhost:8000/test-email`

---

## ✅ **Checklist**

- [ ] Email configured in `.env`
- [ ] Config cache cleared
- [ ] Barcode package installed
- [ ] Testing with NEW registration (status = "uploaded")
- [ ] Checking Laravel logs for errors
- [ ] Using log driver for testing

---

## 🎯 **Most Common Issue**

**You're trying to verify an ALREADY VERIFIED payment!**

The email only sends when changing FROM "uploaded" TO "verified".

**Solution:** Find a registration with status "uploaded" and verify it.

---

**Check the logs and try with a NEW registration!** 📧
