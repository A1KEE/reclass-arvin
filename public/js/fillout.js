  document.addEventListener('DOMContentLoaded', function() {

      function isFormFilled() {
          const name = document.getElementById('name').value.trim();
          const currentPosition = document.querySelector('select[name="current_position"]').value;
          const positionApplied = document.getElementById('position_applied').value;
          const itemNumber = document.querySelector('input[name="item_number"]').value.trim();
          const school = document.getElementById('school_id').value;
          const sgSalary = document.querySelector('input[name="sg_annual_salary"]').value;
          const levels = document.querySelectorAll('input[name="levels[]"]:checked');

          return name && currentPosition && positionApplied && itemNumber && school && sgSalary && levels.length > 0;
      }

      function handleModal(buttonClass, modalId) {
          const btn = document.querySelector(buttonClass);
          btn.addEventListener('click', function() {
              if(!isFormFilled()) {
                  Swal.fire({
                      icon: 'warning',
                      title: 'Oops!',
                      text: 'Please fill out all required fields in the form above before adding Qualifications Standard.',
                      confirmButtonText: 'OK',
                      timer: 5000
                  });
              } else {
                  const modal = new bootstrap.Modal(document.getElementById(modalId));
                  modal.show();
              }
          });
      }

      // Attach handlers for all four buttons
      handleModal('.add-education-btn', 'educationModal');
      handleModal('.add-training-btn', 'trainingModal');
      handleModal('.add-experience-btn', 'experienceModal');
      handleModal('.add-eligibility-btn', 'eligibilityModal');

  });
