document.addEventListener("DOMContentLoaded", function () {

  // Check if user already accepted Data Privacy
  if (!localStorage.getItem("dataPrivacyAccepted")) {

    Swal.fire({
      title: "Data Privacy Notice",
      icon: "info",
      html: `
        <p style="text-align:justify; font-size:14px;">
          By accessing and using this system, you acknowledge and agree that
          all personal information and documents you provide shall be collected,
          processed, stored, and protected in accordance with the
          <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>.
        </p>
        <p style="text-align:justify; font-size:14px;">
          Your information will be used solely for legitimate purposes related
          to application evaluation, verification, and record management, and
          will not be shared with unauthorized parties.
        </p>
        <p style="text-align:justify; font-size:13px; font-style:italic;">
          By clicking <strong>"I Agree"</strong>, you voluntarily consent to the
          collection and processing of your personal data.
        </p>
      `,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: "I Agree",
      confirmButtonColor: "#198754"
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.setItem("dataPrivacyAccepted", "yes");
      }
    });

  }
});
