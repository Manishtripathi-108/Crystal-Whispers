// overlay menu
function openNav() {
    document.getElementById("myNav").classList.toggle("menu_width");
    document.querySelector(".custom_menu-btn").classList.toggle("menu_btn-style");
}

document.addEventListener("DOMContentLoaded", function () {
    // toggleSignup-loginForm
    var loginForm = document.getElementById("login-form");
    var signupForm = document.getElementById("signup-form");

    var toggleSignupLink = document.getElementById("toggle-signup");
    var toggleLoginLink = document.getElementById("toggle-login");

    function toggleForms() {
        loginForm.classList.toggle("d-none");
        signupForm.classList.toggle("d-none");
    }

    toggleSignupLink.addEventListener("click", function (event) {
        toggleForms();
    });

    toggleLoginLink.addEventListener("click", function (event) {
        toggleForms();
    });
    //End toggleSignup-loginForm
});

// Artisans Carousal
let currentSlide = 0;
let teamMembersPerSlide = 3;
function showSlides() {
    const teamMembers = document.querySelectorAll(".team-member");
    for (let i = 0; i < teamMembers.length; i++) {
        teamMembers[i].style.display = "none";
    }

    for (let i = currentSlide; i < currentSlide + teamMembersPerSlide; i++) {
        if (teamMembers[i]) {
            teamMembers[i].style.display = "inline-block";
        }
    }
}

function prevSlide() {
    if (currentSlide > 0) {
        currentSlide -= teamMembersPerSlide;
        showSlides();
    }
}

function nextSlide() {
    const teamMembers = document.querySelectorAll(".team-member");
    if (currentSlide + teamMembersPerSlide < teamMembers.length) {
        currentSlide += teamMembersPerSlide;
        showSlides();
    }
}

function updateTeamMembersPerSlide() {
    if (window.innerWidth < 768) {
        teamMembersPerSlide = 2;
    } else {
        teamMembersPerSlide = 3;
    }
    showSlides();
}
updateTeamMembersPerSlide();
window.addEventListener("resize", updateTeamMembersPerSlide);
//End Artisans Carousal

//for worker update
$(document).ready(function () {
    $('.edit-worker').click(function () {
        var workerId = $(this).data('worker-id');

        $.ajax({
            url: 'php/get_worker_details.php',
            type: 'POST',
            data: { worker_id: workerId },
            success: function (response) {
                $('#editWorkerModal .modal-body').html(response);
            },
            error: function () {
                // console.error('Failed to fetch worker details.');
                $('#editModal .modal-body').html('Failed to fetch product details.');
            }
        });
    });
});
//end worker update

// product update
$(document).ready(function () {
    $('.edit-product').click(function () {
        var productId = $(this).data('product-id');

        $.ajax({
            url: 'php/get_product_details.php',
            type: 'POST',
            data: { product_id: productId },
            success: function (response) {
                $('#editModal .modal-body').html(response);
            },
            error: function () {
                // console.error('Failed to fetch product details.');
                $('#editModal .modal-body').html('Failed to fetch product details.');
            }
        });
    });
});
//end product update

//for image preview
function displaySelectedImage(event, elementId) {
    const selectedImage = document.getElementById(elementId);
    const fileInput = event.target;

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            selectedImage.src = e.target.result;
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}
//end image preview

//for delete id
function setDeleteId(setIdName, IdToDelete) {
    document.getElementById(setIdName).value = IdToDelete;
}

//for description word count
document.getElementById('description').addEventListener('input', function () {
    var words = this.value.split(/\s+/).length;
    var maxWords = 50;

    if (words > maxWords) {
        this.value = this.value.split(/\s+/).slice(0, maxWords).join(' ');
        words = maxWords;
    }

    document.getElementById('wordCount').innerText = 'Write Description (Words remaining: ' + (maxWords - words) + '):';
});

document.getElementById('update_description').addEventListener('input', function () {
    var words = this.value.split(/\s+/).length;
    var maxWords = 50;

    if (words > maxWords) {
        this.value = this.value.split(/\s+/).slice(0, maxWords).join(' ');
        words = maxWords;
    }

    document.getElementById('update_wordCount').innerText = 'Update Description (Words remaining: ' + (maxWords - words) + '):';
});
//end description word count