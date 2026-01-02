# ✅ Backend Adapted to Match Frontend!

## 🎯 **What I Did**

Instead of changing the frontend, I **adapted the backend** to work with your existing Event_Registrations.vue form!

---

## 📋 **Changes Made**

### **1. Restored Original Frontend** ✅
- Restored the original Event_Registrations.vue
- Added **gender field** (required for BIB generation)
- Kept all original fields: ticket_type, ticket_price, transaction_id, agreement

### **2. Updated Backend Controller** ✅
- Updated `EventRegistrationController` to accept all frontend fields
- Maps `ticket_type` → `payment_method` in database
- Handles payment proof upload during registration
- Uses `EventRegistration` model
- Auto-generates registration code

### **3. Fixed Routes** ✅
- Made registration routes **public** (no auth required)
- Added `/event/register` route (matches frontend)
- Kept `/event-registrations` route (alternative)

---

## 🔄 **How It Works Now**

### **Frontend Sends:**
```javascript
{
    name, nik, address, phone, email,
    gender,  // ← NEW!
    illness, shirt_size,
    ticket_type,      // e.g., "Tiket Basic"
    ticket_price,     // e.g., 100000
    transaction_id,   // Optional
    payment_proof,    // Image file
    agreement         // Checkbox
}
```

### **Backend Receives & Processes:**
```php
// Validates all fields
// Uploads payment proof to storage/app/public/payment-proofs/
// Creates EventRegistration with:
{
    name, nik, address, phone, email, gender,
    illness, shirt_size,
    payment_method: ticket_type,  // ← Mapped!
    payment_proof_path,
    payment_proof_filename,
    payment_status: 'uploaded',   // Already has payment
    registration_code: 'REG-ABC123XYZ'
}
```

### **Backend Returns:**
```php
redirect()->back()->with('success', [
    'message' => 'Pendaftaran berhasil!',
    'registration_code' => 'REG-ABC123XYZ',
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'ticket_type' => 'Tiket Basic',
    'ticket_price' => 100000
]);
```

---

## 📝 **Form Fields**

### **Required:**
- ✅ Name
- ✅ NIK (unique)
- ✅ Address
- ✅ Phone
- ✅ Email
- ✅ **Gender (M/F)** ← NEW!
- ✅ Shirt Size (M/L/XL)
- ✅ Ticket Type
- ✅ Ticket Price
- ✅ Payment Proof (image)
- ✅ Agreement (checkbox)

### **Optional:**
- Illness
- Transaction ID

---

## 🎨 **Frontend Updates**

### **Added Gender Field:**
```vue
<div>
    <label class="label">Jenis Kelamin</label>
    <select v-model="form.gender" class="input">
        <option disabled value="">Pilih Jenis Kelamin</option>
        <option value="M">Laki-laki</option>
        <option value="F">Perempuan</option>
    </select>
</div>
```

**Location:** Between Email and Riwayat Penyakit fields

---

## 🔌 **API Endpoints**

### **Registration Form:**
```
GET /event-registrations
```
Shows the registration form

### **Submit Registration:**
```
POST /event/register
POST /event-registrations
```
Both work! (Same controller)

**Request Type:** `multipart/form-data` (for file upload)

---

## 🧪 **Testing**

### **Test the Form:**
1. Go to `http://localhost:8000/event-registrations`
2. Fill all fields (including gender!)
3. Select ticket type
4. Upload payment proof
5. Check agreement
6. Click "Konfirmasi Pembayaran"
7. Should see success message with registration code

### **Validation:**
- ✅ NIK must be unique
- ✅ Gender is required
- ✅ Payment proof must be image (max 5MB)
- ✅ Agreement must be checked

---

## 📊 **Data Flow**

```
User fills form
    ↓
Selects ticket type (Basic/Fun Run)
    ↓
Uploads payment proof
    ↓
Checks agreement
    ↓
Submits form
    ↓
Backend validates
    ↓
Uploads payment proof to storage
    ↓
Creates EventRegistration
    - payment_status: 'uploaded'
    - registration_code: auto-generated
    - bib_number: null (assigned on approval)
    ↓
Returns success with registration code
    ↓
Admin sees registration in admin panel
    ↓
Admin approves payment
    ↓
System generates BIB number (M5001/F5001)
    ↓
Email sent with BIB & barcode
    ↓
User receives confirmation! 🎉
```

---

## 🔐 **Routes Configuration**

### **Public Routes (No Login):**
```php
GET  /event-registrations  → Show form
POST /event/register       → Submit registration
POST /event-registrations  → Submit registration (alternative)
```

### **Admin Routes (Login Required):**
```php
GET   /admin/registrations              → View all
GET   /admin/registrations/{id}         → View details
PATCH /admin/registrations/{id}/approve → Approve (generates BIB, sends email)
PATCH /admin/registrations/{id}/reject  → Reject
GET   /admin/registrations/export/csv   → Export CSV
```

---

## ✅ **What's Different from Before**

| Aspect | Before | Now |
|--------|--------|-----|
| **Frontend** | Changed completely | ✅ **Kept original** (just added gender) |
| **Backend** | Separate API | ✅ **Adapted to match frontend** |
| **Payment Upload** | Separate step | ✅ **Together with registration** |
| **Ticket Selection** | Removed | ✅ **Kept** (Basic/Fun Run) |
| **Gender Field** | Not present | ✅ **Added** (required for BIB) |
| **Routes** | Auth required | ✅ **Public** (no login needed) |

---

## 🎯 **Key Features**

### **1. All-in-One Registration** ✅
- User fills form once
- Uploads payment immediately
- No separate steps

### **2. Ticket Selection** ✅
- Tiket Basic: Rp 100,000
- Tiket Fun Run + Support: Rp 130,000

### **3. Payment Proof Upload** ✅
- Uploaded during registration
- Stored in `storage/app/public/payment-proofs/`
- Status automatically set to 'uploaded'

### **4. Auto BIB Generation** ✅
- Generated when admin approves
- Format: M5001 (Male) or F5001 (Female)
- Based on gender field

### **5. Email with Barcode** ✅
- Sent when admin approves
- Includes BIB number
- Includes scannable barcode

---

## 📝 **Important Notes**

### **Gender is Required!**
- Added to form validation
- Required for BIB number generation
- Options: M (Laki-laki) or F (Perempuan)

### **Payment Status Flow:**
```
Registration → 'uploaded' (has payment proof)
    ↓
Admin approves → 'verified' (BIB generated, email sent)
    OR
Admin rejects → 'rejected' (can re-upload via admin)
```

### **NIK Must Be Unique:**
- Each person can only register once
- Validation error if NIK already exists

---

## 🎉 **Summary**

**Instead of changing your frontend, I adapted the backend to work with it!**

✅ **Frontend:** Original form + gender field  
✅ **Backend:** Updated to accept all frontend fields  
✅ **Routes:** Public, no login required  
✅ **Payment:** Uploaded together with registration  
✅ **BIB:** Auto-generated on approval  
✅ **Email:** Sent with barcode on approval  

**Everything works together now!** 🎊

---

**Test it at:** `http://localhost:8000/event-registrations`
