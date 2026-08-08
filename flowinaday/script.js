window.reserveClass = function() {
  // check longin state
  const isLoggedIn = (window.isLoggedIn === true || window.isLoggedIn === "true");
  console.log("Checking login status:", window.isLoggedIn, "Type:", typeof window.isLoggedIn);
  console.log("isLoggedIn result:", isLoggedIn);

  if (!isLoggedIn) {
    showMessage("Please log in to book the class.");
    return;
  }

  // check booking modal
  const confirmModal = document.getElementById('confirmModal');
  if (!confirmModal) {
    console.log("confirmModal not found!");
    return;
  }
  confirmModal.classList.remove('hidden');

  const yesBtn = document.getElementById('confirmYesBtn');
  const noBtn = document.getElementById('confirmNoBtn');

  if (!yesBtn || !noBtn) {
    console.log("confirmYesBtn or confirmNoBtn not found!");
    return;
  }

  yesBtn.replaceWith(yesBtn.cloneNode(true));
  noBtn.replaceWith(noBtn.cloneNode(true));

  const newYesBtn = document.getElementById('confirmYesBtn');
  const newNoBtn = document.getElementById('confirmNoBtn');

  if (!newYesBtn || !newNoBtn) {
    console.log("newYesBtn or newNoBtn not found!");
    return;
  }

  newYesBtn.addEventListener('click', () => {
    confirmModal.classList.add('hidden');
    proceedBooking();
  });

  newNoBtn.addEventListener('click', () => {
    confirmModal.classList.add('hidden');
  });
};

function proceedBooking() {
  const classTitleElement = document.getElementById('classTitle');
  if (!classTitleElement) {
    console.log("classTitleElement not found!");
    showMessage("Class information not found.");
    return;
  }

  const classId = classTitleElement.dataset.classid;
  if (!classId) {
    console.log("classId not found!");
    showMessage("Class ID not found.");
    return;
  }

  console.log("Sending classId:", classId);

  fetch('reserve.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ classid: classId })
  })
  .then(response => {
    console.log("Response status:", response.status);
    return response.text();
  })
  .then(text => {
    console.log("Raw response:", text);
    try {
      const data = JSON.parse(text);
      if (data.success) {
        showMessage("Booking confirmed!", true);
      } else {
        console.log("Booking failed:", data.message);
        showMessage("Error: " + data.message);
      }
    } catch (e) {
      console.log("JSON parse error:", e);
      showMessage("Server error occurred");
    }
  })
  .catch(err => {
    console.log("Fetch error:", err);
    showMessage("Error occurred! Please try again");
  });
}

function showMessage(message, isSuccess = false) {
  const messageModal = document.getElementById('messageModal');
  const messageText = document.getElementById('messageText');
  const closeBtn = document.getElementById('messageCloseBtn');
  const loginBtn = document.getElementById('loginBtn');

  if (!messageModal || !messageText || !closeBtn || !loginBtn) {
    console.log("One or more modal elements not found!");
    return;
  }

  messageText.innerText = message;
  messageModal.classList.remove('hidden');
  
 
const hideLoginMessages = [
  "You have already booked this class.",
  "Class is full",
  "Booking not allowed"
];

const shouldHideLogin = isSuccess || hideLoginMessages.some(msg => message.includes(msg));

if (shouldHideLogin) {
  loginBtn.classList.add('hidden');
} else {
  loginBtn.classList.remove('hidden');
}

  closeBtn.replaceWith(closeBtn.cloneNode(true));
  const newCloseBtn = document.getElementById('messageCloseBtn');
  newCloseBtn.addEventListener('click', () => {
    messageModal.classList.add('hidden');
  });

  loginBtn.replaceWith(loginBtn.cloneNode(true));
  const newLoginBtn = document.getElementById('loginBtn');
  newLoginBtn.addEventListener('click', () => {
    window.location.href = "login.php";
  });
}

window.showDetails = function(element) {
  const classTitle = document.getElementById('classTitle');
  if (!classTitle) {
    console.log("classTitle not found!");
    return;
  }

  classTitle.innerText = element.dataset.name;
  classTitle.dataset.classid = element.dataset.classid;

  document.getElementById('classDescription').innerText = element.dataset.description;
  document.getElementById('classInstructor').innerText = "Instructor: " + element.dataset.instructor;
  document.getElementById('classSchedule').innerText = "Schedule: " + element.dataset.schedule;
  document.getElementById('classPrice').innerText = "Price: $" + element.dataset.price;
  document.getElementById('classCapacity').innerText = "Capacity: " + element.dataset.capacity;
  document.getElementById('classImage').src = element.dataset.image;

  document.getElementById('myModal').classList.remove('hidden');
};

window.closeModal = function() {
  const modal = document.getElementById('myModal');
  if (modal) {
    modal.classList.add('hidden');
  }
};

document.addEventListener("DOMContentLoaded", () => {
  const isLoggedIn = (window.isLoggedIn === true || window.isLoggedIn === "true");
  if (isLoggedIn) {
    console.log("User is logged in.");
  } else {
    console.log("User is NOT logged in.");
  }
});

$(document).ready(function(){
  $('.btn-delete-class').on('click', function(e){
      if (!confirm('Are you sure you want to delete this class?')) {
          e.preventDefault();
      }
  });

  $('.btn-delete-user').on('click', function(e){
      if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
          e.preventDefault();
      }
  });
});

//toggle menu
document.addEventListener('DOMContentLoaded', function() {
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  navToggle.addEventListener('click', function() {
    navLinks.classList.toggle('active');
  });
});


