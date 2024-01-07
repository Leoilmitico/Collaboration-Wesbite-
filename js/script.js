let navbar = document.querySelector('.header .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.add('active');
}

document.querySelector('#nav-close').onclick = () =>{
    navbar.classList.remove('active');
}

let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>{
    searchForm.classList.add('active');
}

document.querySelector('#close-search').onclick = () =>{
    searchForm.classList.remove('active');
}

window.onscroll = () =>{
    navbar.classList.remove('active');

    if(window.scrollY > 0){
        document.querySelector('.header').classList.add('active');
    }else{
        document.querySelector('.header').classList.remove('active');
    }
};

window.onload = () =>{
    if(window.scrollY > 0){
        document.querySelector('.header').classList.add('active');
    }else{
        document.querySelector('.header').classList.remove('active');
    }
};


var swiper = new Swiper(".home-slider", {
    loop:true,
    grabCursor:true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

var swiper = new Swiper(".product-slider", {
    loop:true,
    grabCursor:true,
    spaceBetween: 20,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        640: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        1024: {
            slidesPerView: 4,
        },
    },
});

var swiper = new Swiper(".review-slider", {
    loop:true,
    grabCursor:true,
    spaceBetween: 20,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        640: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
    },
});

var swiper = new Swiper(".blogs-slider", {
    loop:true,
    grabCursor:true,
    spaceBetween: 10,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        991: {
            slidesPerView: 3,
        },
    },
});

var swiper = new Swiper(".clients-slider", {
    loop:true,
    grabCursor:true,
    spaceBetween: 20,
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        640: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        1024: {
            slidesPerView: 4,
        },
    },
});
document.addEventListener('DOMContentLoaded', function () {
    const readMoreBtns = document.querySelectorAll('.read-more-btn');

    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const contentDiv = document.getElementById('post-content-' + postId);

            if (contentDiv.style.maxHeight && contentDiv.style.maxHeight !== 'none') {
                contentDiv.style.maxHeight = 'none';
                this.textContent = 'Read Less';
            } else {
                contentDiv.style.maxHeight = '100px';
                this.textContent = 'Read More';
            }
        });
    });
});


document.querySelectorAll('.upvote-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        // Get the post ID associated with the clicked button from the "data-post-id" attribute
        const postId = this.dataset.postId;

        // Makes an AJAX call to the "home.php" page with the "action=upvote" parameter and the post ID in the body, and a cache-busting parameter
        fetch('home.php?action=upvote&cache=' + new Date().getTime(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'post_id=' + postId
        })
            // Parse the response data as JSON
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // If successful, update the upvote count displayed next to the button
                    btn.querySelector('.upvotes-count').textContent = data.upvotes;
                }
            })
            // If there was an error with the AJAX call, display it in the console
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
// Remove special charcters to prevent sql inject
function removeSpecialChars(input) {
    var regex = /[^a-zA-Z0-9\s.;:!?\(\)]/g;
    input.value = input.value.replace(regex, '');
}
(function() {
    if (window.location.search.includes("request_error=2")) {
        const newUrl = window.location.href.replace("&request_error=2", "").replace("?request_error=2", "");
        window.history.replaceState(null, null, newUrl);

        // Show the custom modal with the error message
        var modal = document.getElementById("myModal");
        var message = document.getElementById("modal-message");
        message.innerHTML = "You have already sent a join request for this post";
        modal.style.display = "block";
    }
})();

var closeBtn = document.getElementById("close");
closeBtn.onclick = function() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
};


// Get the post-author element
const postAuthor = document.querySelectorAll('.post-author');

// Loop through each post-author element
postAuthor.forEach(element => {
    // Add event listener for hovering over the element
    element.addEventListener('mouseenter', (event) => {
        // Get the name from the data-name attribute
        const name = element.getAttribute('data-name');
        // Create the blank box
        const box = document.createElement('div');
        box.classList.add('preview-box');
        // Add the name to the box
        const nameElement = document.createElement('p');
        nameElement.textContent = name;
        box.appendChild(nameElement);
        // Add the box to the profile-preview element
        const preview = document.getElementById('profile-preview');
        preview.innerHTML = '';
        preview.appendChild(box);
    });
    // Add event listener for leaving the element
    element.addEventListener('mouseleave', (event) => {
        // Remove the blank box from the profile-preview element
        const preview = document.getElementById('profile-preview');
        preview.innerHTML = '';
    });
});