# 🐛 Registration Not Saving to Database - Troubleshooting

## ✅ **Fixed Issues**

### 1. **Gender Value Mismatch** ✅
**Problem:** Frontend was sending `Male`/`Female` but backend expects `M`/`F`

**Fixed:**
```vue
<!-- Before -->
<option value="Male">Laki-Laki</option>
<option value="Female">Perempuan</option>

<!-- After -->
<option value="M">Laki-Laki</option>
<option value="F">Perempuan</option>
```

---

## 🔍 **Checklist to Debug**

### **1. Check Browser Console**
Open browser DevTools (F12) and check:
- ✅ Is the form submitting?
- ✅ Any JavaScript errors?
- ✅ Check Network tab for the POST request
- ✅ What's the response status? (200, 422, 500?)

### **2. Check Laravel Logs**
```bash
# View latest errors
tail -f storage/logs/laravel.log
```

Look for:
- Validation errors
- Database errors
- File upload errors

### **3. Check Form Data**
In browser console, before submitting:
```javascript
console.log(form.data())
```

Should show:
```javascript
{
    name: "John Doe",
    nik: "1234567890123456",
    address: "Jl. Example",
    phone: "081234567890",
    email: "john@example.com",
    gender: "M",  // ← Must be M or F
    illness: "",
    shirt_size: "L",
    ticket_type: "Tiket Basic",
    ticket_price: 100000,
    payment_method: "QRIS",
    transaction_id: "",
    payment_proof: File,  // ← Must be a File object
    agreement: true
}
```

### **4. Check Validation**
Backend expects:
```php
'gender' => 'required|in:M,F',  // ← Must be M or F
'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
```

### **5. Check Database**
```sql
SELECT * FROM event_registrations ORDER BY created_at DESC LIMIT 5;
```

---

## 🧪 **Test Steps**

1. **Open browser DevTools** (F12)
2. **Go to Network tab**
3. **Fill the form:**
   - Name: Test User
   - NIK: 1234567890123456
   - Address: Test Address
   - Phone: 081234567890
   - Email: test@example.com
   - Gender: Laki-Laki (M)
   - Shirt Size: L
   - Select ticket: Tiket Basic
   - Upload payment proof (JPG/PNG)
   - Check agreement
4. **Click "Konfirmasi Pembayaran"**
5. **Check Network tab:**
   - Find POST request to `/event-registrations`
   - Check status code
   - Check response

---

## 🔴 **Common Errors**

### **422 Validation Error**
**Cause:** Form data doesn't pass validation

**Check:**
- Gender must be "M" or "F" (not "Male"/"Female")
- Payment proof must be image file
- All required fields filled
- NIK must be unique

**Solution:**
```javascript
// Check browser console for validation errors
form.post('/event-registrations', {
    onError: (errors) => {
        console.log('Validation errors:', errors)
    }
})
```

### **500 Server Error**
**Cause:** Backend error (database, file upload, etc.)

**Check:**
```bash
tail -f storage/logs/laravel.log
```

**Common causes:**
- Database connection issue
- Storage directory not writable
- Missing EventRegistration model

### **419 CSRF Token Mismatch**
**Cause:** CSRF token expired

**Solution:**
- Refresh the page
- Check if `@csrf` is in the form

---

## 📝 **Current Form Configuration**

### **Frontend (Event_Registrations.vue)**
```javascript
form.post('/event-registrations', {
    forceFormData: true,  // ← Important for file upload
    preserveScroll: true,
    onSuccess: () => {
        alert('Pendaftaran berhasil!')
    },
    onError: (errors) => {
        console.error('Errors:', errors)
        alert('Terjadi kesalahan.')
    }
})
```

### **Backend (EventRegistrationController.php)**
```php
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:event_registrations,nik',
            'gender' => 'required|in:M,F',  // ← Must be M or F
            'payment_proof' => 'required|image|...',
            // ... other fields
        ]);
        
        // Upload file
        // Create registration
        // Return success
        
    } catch (ValidationException $e) {
        return redirect('/')->with('error', ...);
    }
}
```

---

## ✅ **What to Check Right Now**

1. **Open browser console** and try to submit
2. **Check Network tab** for the POST request
3. **Look at the response:**
   - Status 200 = Success
   - Status 422 = Validation error (check response body)
   - Status 500 = Server error (check Laravel logs)

4. **If 422 error, check:**
   - Is gender "M" or "F"?
   - Is payment_proof a valid image?
   - Are all required fields filled?

5. **If 500 error, check:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 🎯 **Quick Fix Checklist**

- ✅ Gender values changed to M/F
- ✅ Form validation includes gender
- ✅ Route exists: POST /event-registrations
- ✅ Controller method exists
- ⏳ **Test the form now!**

---

**Try submitting the form now and check the browser console!**
