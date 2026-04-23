document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("applicantForm");
    const submitBtn = document.getElementById("submitBtn");

    let isSubmitting = false;

    submitBtn.addEventListener("click", async function (e) {
        e.preventDefault();

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
        // EMAIL INPUT + CHECK
        // =========================
        const { value: email, isConfirmed } = await Swal.fire({
            title: 'Enter Your Email',
            html: `
                <p style="margin-bottom:10px;">We will send your application status here:</p>
                <input type="email" id="swal_email" class="swal2-input" placeholder="Enter your email">
            `,
            confirmButtonText: 'Continue',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false,
            preConfirm: async () => {

                const email = document.getElementById('swal_email')?.value.trim();

                if (!email) {
                    Swal.showValidationMessage('Email is required');
                    return false;
                }

                try {
                    const res = await fetch("/check-email", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ email })
                    });

                    const data = await res.json();

                    // 🔥 FINAL RULE: 1 APPLICATION ONLY
                    if (data.count >= 1) {
                        Swal.showValidationMessage(
                            'You already have an existing application. Only one email is allowed.'
                        );
                        return false;
                    }

                    return email;

                } catch (err) {
                    Swal.showValidationMessage('Network error. Try again.');
                    return false;
                }
            }
        });

        if (!isConfirmed) return;

        document.getElementById("applicant_email").value = email;

        // =========================
        // CONFIRM SUBMIT
        // =========================
        const confirm = await Swal.fire({
            title: 'Submit Application?',
            text: "This cannot be edited after submission.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false
        });

        if (!confirm.isConfirmed) return;

        // =========================
        // LOCK UI
        // =========================
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = "Submitting... ⏳";

        Swal.fire({
            title: 'Submitting Application...',
            html: 'Please wait while we process your application.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {

            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            });

            // =========================
            // SAFE JSON PARSING (IMPORTANT FIX)
            // =========================
            const text = await response.text();

            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("NON-JSON RESPONSE:", text);
                throw new Error("Server error: invalid response format");
            }

            // =========================
            // ERROR HANDLING
            // =========================
            if (!response.ok) {

                if (response.status === 422) {
                    let msg = "Validation error";

                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join("\n");
                    } else if (data.message) {
                        msg = data.message;
                    }

                    throw new Error(msg);
                }

                throw new Error(data.message || "Server error");
            }

            // =========================
            // SUCCESS
            // =========================
            await Swal.fire({
                icon: 'success',
                title: 'Submitted!',
                text: 'Application submitted successfully.'
            });

            window.location.reload();

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: error.message
            });

            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Submit Application";
        }

    });

});