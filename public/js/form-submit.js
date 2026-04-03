document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("applicantForm");
    const submitBtn = document.getElementById("submitBtn");

    let isSubmitting = false;

    submitBtn.addEventListener("click", async function () {

        if (isSubmitting) return;

        const name = document.getElementById("name")?.value;
        const position = document.getElementById("position_applied")?.value;
        const school = document.getElementById("school_id")?.value;

        if (!name || !position || !school) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please fill out all required fields.'
            });
            return;
        }

        // =========================
        // 📧 ASK EMAIL BEFORE SUBMIT
        // =========================
        const { value: email, isConfirmed } = await Swal.fire({
            title: 'Enter Your Email',
            html: `
                <p style="margin-bottom:10px;">We will send your application status here:</p>
                <input type="email" id="swal_email" class="swal2-input" placeholder="Enter your email" required>
            `,
            confirmButtonText: 'Continue',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false,
            focusConfirm: false,
           preConfirm: async () => {

    const email = document.getElementById('swal_email')?.value.trim();

    if (!email) {
        Swal.showValidationMessage('Email is required');
        return false;
    }

    const token = document.querySelector('meta[name="csrf-token"]');

    if (!token) {
        Swal.showValidationMessage('Security token missing. Please refresh.');
        return false;
    }

    try {

        const res = await fetch("/check-email", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token.content
            },
            body: JSON.stringify({ email: email })
        });

        if (!res.ok) {
            throw new Error("HTTP error " + res.status);
        }

        const data = await res.json();

        if (data.count >= 2) {
            Swal.showValidationMessage(
                'Maximum of 2 applications reached. Use another email.'
            );
            return false;
        }

        return email;

    } catch (error) {
        Swal.showValidationMessage('Request failed. Refresh page.');
        return false;
    }
}
        });

        if (!isConfirmed) return;

        // ✅ SET EMAIL TO HIDDEN INPUT
        document.getElementById("applicant_email").value = email;

        // =========================
        // CONFIRM SUBMIT
        // =========================
        const confirm = await Swal.fire({
            title: 'Submit Application?',
            text: "You will not be able to edit this after submission.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false
        });

        if (!confirm.isConfirmed) return;

        // =========================
        // FINAL SUBMIT
        // =========================
        isSubmitting = true;

        submitBtn.disabled = true;
        submitBtn.innerHTML = "Submitting... ⏳";

        showSubmitLoading();

        $('#applicantForm').trigger('submit');

    });

});