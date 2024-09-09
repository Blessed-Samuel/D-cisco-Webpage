const dropdown = document.querySelector(".dropdown");
const dropdown_menu = document.querySelector(".dropdown-menu");
const catlink = document.querySelector(".cat-link");

const nav = document.querySelector(".nav-links");
const burger = document.querySelector(".burger");
const close = document.querySelector(".close-icon");

burger.addEventListener("click", () => {
    nav.style.display = "flex";
    burger.style.display = "none";
    close.style.display = "block";
});

close.addEventListener("click", () => {
    nav.style.display = "none";
    burger.style.display = "block";
    close.style.display = "none";
});

catlink.addEventListener('click', function(event) {
    event.preventDefault(); // Prevents the default behavior of the <a> tag
    dropdown_menu.style.display = (dropdown_menu.style.display !== "flex") ? "flex" : "none";
});



var landingContent = [
    { imagePath: "images/man.png", text1: "Master High Paying Tech Courses and Advance Your Career", text2:" Comprehensive training programs for Cisco certifications and cutting-edge tech skills" },
    { imagePath: "images/hero2.png", text1: "We Create Innovation Through Digital Transformation", text2: "DCisco Academy is a leading provider of comprehensive technology training solutions and digital services, empowering individuals and businesses to thrive in the ever-evolving tech landscape. With a team of certified instructors, skilled developers, and industry experts, we offer a wide range of programs and services tailored to meet the specific needs of our clients."}
  ];

  var currentIndex = 0; // Variable to track the current index in the landingContent array
  hd=document.getElementById("hd")


  window.onload = function() {
    changeLandingContent(); // Call changeLandingContent function when the window loads
    setInterval(changeLandingContent, 10000); // Call changeLandingContent function every 10 seconds
  };

  function changeLandingContent() {
    var currentContent = landingContent[currentIndex];
    var landingImage = document.getElementById("landing-image");
    var landingText1 = document.getElementById("landing-text1");
    var landingText2 = document.getElementById("landing-text2");

    // Apply transition effect by changing transform and opacity
    landingImage.style.transform = "scale(0.5)";
    landingText1.style.transform = "scale(0.5)";
    landingText2.style.transform = "scale(0.5)";
    landingImage.style.opacity = 0;
    landingText1.style.opacity = 0;
    landingText2.style.opacity = 0;

    setTimeout(function() {
      // Update image source and text with the current content
      landingImage.src = currentContent.imagePath;
      landingText1.textContent = currentContent.text1;
      landingText2.textContent = currentContent.text2;

      // Apply transition effect by changing transform and opacity
      landingImage.style.transform = "scale(1)";
      landingText1.style.transform = "scale(1)";
      landingText2.style.transform = "scale(1)";
      landingImage.style.opacity = 1;
      landingText1.style.opacity = 1;
      landingText2.style.opacity = 1;

      // Toggle class for styling the second image differently
      if (currentIndex === 1) {
        landingImage.classList.add('second-image');
        landingText1.classList.add('text1');
        landingText2.classList.add('text2');
        hd.style.backgroundColor = "#BC6DA4";
      } else {
        landingImage.classList.remove('second-image');
        landingText1.classList.remove('text1');
        landingText2.classList.remove('text2');
        hd.style.backgroundColor = "#4800BD";
      }

      // Increment the index for the next content, loop back to 0 if reached the end
      currentIndex = (currentIndex + 1) % landingContent.length;
    }, 300); // Wait for 500ms before updating content
  }





  function toggleDropdown() {
    var dropdownOptions = document.getElementById("dropdown-options");
    if (dropdownOptions.style.display === "block") {
        dropdownOptions.style.display = "none";
    } else {
        dropdownOptions.style.display = "block";
    }
}

// Close dropdown if user clicks outside the dropdown or its related elements
window.onclick = function(event) {
    var dropdownOptions = document.getElementById("dropdown-options");
    var dropdownToggle = document.querySelector('.dropdown-toggle');

    if (!event.target.matches('.dropdown-toggle') && !event.target.matches('#dropdown-options') && !dropdownOptions.contains(event.target)) {
        if (dropdownOptions.style.display === "block") {
            dropdownOptions.style.display = "none";
        }
    }
}



function updateCheckboxValues() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(function(checkbox) {
      var label = document.querySelector('label[for="' + checkbox.id + '"]');
      checkbox.value = label.textContent;
  });
}




    



