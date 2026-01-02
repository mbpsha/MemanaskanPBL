# 📧 Payment Approval Email with Barcode & BIB Number

## ✅ **Implementation Complete!**

I've implemented the payment approval notification system with barcode generation and automatic BIB number assignment.

---

## 🎯 **Features Implemented**

### 1. **Auto-Generated BIB Numbers**
- Format: `M5001` (Male) or `F5001` (Female)
- **M/F** = Gender (Male/Female)
- **5000** = 5K race identifier
- **01** = Sequential participant number

### 2. **Barcode Generation**
- Barcode generated from registration code
- Format: Code 128 (industry standard)
- Used for race pack pickup
- Embedded in email as base64 image

### 3. **Email Notification**
- Sent automatically when admin approves payment
- Includes:
  - ✅ BIB number (large, prominent display)
  - ✅ Barcode for race pack pickup
  - ✅ Registration details
  - ✅ T-shirt size
  - ✅ Important instructions

---

## 📊 **Database Changes**

### New Fields in `event_registrations` Table:
```sql
- gender (enum: 'M', 'F') - Required for BIB generation
- bib_number (string, unique) - Auto-generated on approval
```

---

## 🔧 **How It Works**

### Registration Flow:
```
1. User registers with gender (M/F)
   ↓
2. User uploads payment proof
   ↓
3. Admin approves payment
   ↓
4. System auto-generates BIB number:
   - First Male: M5001
   - Second Male: M5002
   - First Female: F5001
   - Second Female: F5002
   ↓
5. System generates barcode from registration code
   ↓
6. Email sent with:
   - BIB number
   - Barcode image
   - Instructions
   ↓
7. User receives email and can:
   - See their BIB number
   - Print/save barcode for pickup
```

---

## 📧 **Email Content**

### What Users Receive:

1. **Large BIB Number Display**
   - Prominent, easy to read
   - Example: `M5001` or `F5001`

2. **Registration Details**
   - Registration code
   - BIB number
   - T-shirt size
   - Verification date

3. **Barcode Image**
   - Scannable barcode
   - Registration code below
   - Instructions to show at pickup

4. **Important Instructions**
   - Save the email
   - Bring ID card
   - Show barcode at pickup
   - Remember BIB number

---

## 🎨 **BIB Number Format Examples**

| Gender | Participant # | BIB Number | Meaning |
|--------|---------------|------------|---------|
| Male | 1st | `M5001` | Male, 5K, #1 |
| Male | 2nd | `M5002` | Male, 5K, #2 |
| Male | 10th | `M5010` | Male, 5K, #10 |
| Female | 1st | `F5001` | Female, 5K, #1 |
| Female | 2nd | `F5002` | Female, 5K, #2 |
| Female | 100th | `F5100` | Female, 5K, #100 |

---

## 🔐 **BIB Number Generation Logic**

```php
private function generateBibNumber($gender)
{
    // Get last BIB for this gender
    $lastRegistration = EventRegistration::where('gender', $gender)
        ->whereNotNull('bib_number')
        ->orderBy('bib_number', 'desc')
        ->first();

    if ($lastRegistration && $lastRegistration->bib_number) {
        // Increment from last number
        $lastNumber = (int) substr($lastRegistration->bib_number, -4);
        $nextNumber = $lastNumber + 1;
    } else {
        // Start from 5001
        $nextNumber = 5001;
    }

    // Return: M5001 or F5001
    return $gender . $nextNumber;
}
```

**Features:**
- ✅ Separate sequences for Male/Female
- ✅ Starts from 5001 (5K identifier)
- ✅ Auto-increments
- ✅ Unique per participant

---

## 📦 **Package Installed**

```bash
composer require picqer/php-barcode-generator
```

**Purpose:** Generate Code 128 barcodes for race pack pickup

---

## 🧪 **Testing**

### Test the Complete Flow:

1. **Register with Gender**
```json
POST /api/register
{
    "name": "John Doe",
    "nik": "1234567890123456",
    "address": "Jl. Sudirman 123",
    "phone": "081234567890",
    "email": "john@example.com",
    "gender": "M",  // ← Required: M or F
    "illness": "None",
    "shirt_size": "L",
    "payment_method": "Bank Transfer"
}
```

2. **Upload Payment Proof**
```
POST /api/register/REG-ABC123/payment
FormData: payment_proof = [image]
```

3. **Admin Approves Payment**
```
Admin Panel → View Registration → Approve
```

4. **System Actions:**
   - ✅ Generates BIB: `M5001`
   - ✅ Generates barcode from registration code
   - ✅ Sends email with both

5. **User Receives Email:**
   - ✅ BIB number displayed prominently
   - ✅ Barcode image embedded
   - ✅ Instructions included

---

## 📝 **API Changes**

### Registration Endpoint Updated:
```
POST /api/register

NEW REQUIRED FIELD:
- gender: "M" or "F"
```

**Frontend must now collect gender during registration!**

---

## ✅ **Files Modified/Created**

### Database:
- ✅ `2026_01_01_105216_add_gender_and_bib_to_event_registrations.php`

### Models:
- ✅ `app/Models/EventRegistration.php` - Added gender, bib_number

### Controllers:
- ✅ `app/Http/Controllers/Api/RegistrationController.php` - Added gender validation
- ✅ `app/Http/Controllers/Admin/RegistrationManagementController.php` - Added BIB generation

### Mail:
- ✅ `app/Mail/PaymentApprovedMail.php` - Added barcode generation
- ✅ `resources/views/emails/payment-approved.blade.php` - Updated template

---

## 🎯 **Admin Panel Updates**

When admin approves payment:
1. ✅ BIB number auto-generated
2. ✅ Barcode created
3. ✅ Email sent with both
4. ✅ Success message: "Payment approved, BIB number assigned, and email sent!"

---

## 📧 **Email Configuration**

Make sure `.env` has email settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@webrunning.com
MAIL_FROM_NAME="WebRunning"
```

For testing:
```env
MAIL_MAILER=log  # Emails saved to storage/logs/laravel.log
```

---

## 🎨 **Email Preview**

The email includes:

```
┌─────────────────────────────────┐
│   ✅ Payment Approved!          │
└─────────────────────────────────┘

Dear John Doe,

Congratulations! Your payment has been verified...

┌─────────────────────────────────┐
│                                 │
│           M5001                 │
│                                 │
│      Your BIB Number            │
└─────────────────────────────────┘

Registration Details:
- Registration Code: REG-ABC123
- BIB Number: M5001
- T-Shirt Size: L
- Payment Verified: 01 January 2026, 17:51

┌─────────────────────────────────┐
│  Race Pack Pickup Barcode       │
│                                 │
│  ▌▌ ▌ ▌▌▌ ▌ ▌▌ ▌▌▌ ▌ ▌▌        │
│  ▌▌ ▌ ▌▌▌ ▌ ▌▌ ▌▌▌ ▌ ▌▌        │
│                                 │
│      REG-ABC123                 │
└─────────────────────────────────┘

📋 Important Instructions:
- Save this email
- Bring your ID
- Show the barcode
- Your BIB number is M5001
```

---

## ✅ **Summary**

| Feature | Status | Details |
|---------|--------|---------|
| BIB Number Generation | ✅ Working | M5001/F5001 format |
| Barcode Generation | ✅ Working | Code 128, base64 embedded |
| Email Notification | ✅ Working | Auto-sent on approval |
| Gender Field | ✅ Added | Required for registration |
| Sequential Numbering | ✅ Working | Separate for M/F |

---

## 🚀 **Next Steps for Frontend**

**Frontend team must:**
1. Add gender field to registration form
2. Options: "Male" (M) or "Female" (F)
3. Make it required
4. Send in API request

**Example:**
```javascript
{
    name: "John Doe",
    // ... other fields ...
    gender: "M",  // ← Add this!
    // ... rest of fields ...
}
```

---

## 🎉 **All Done!**

**The system now:**
- ✅ Auto-generates BIB numbers (M5001/F5001)
- ✅ Creates barcodes for race pack pickup
- ✅ Sends beautiful emails with both
- ✅ Separates Male/Female sequences
- ✅ Starts from 5001 (5K identifier)

**Ready to use!** 🏃‍♂️🏃‍♀️
