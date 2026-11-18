/**
 * FoodBook - Client-side Form Validation Library
 * Student Name : Ishara Nishshanka
 * Registration : ITT/2021/076
 * Course       : ICT 1209 – Web Technologies Mini Project
 */

// Basic Validators
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(String(email).trim());
}

function validatePassword(pwd) {
  // At least 8 characters, at least 1 uppercase, at least 1 number
  const minLength = pwd.length >= 8;
  const hasUpper = /[A-Z]/.test(pwd);
  const hasNumber = /[0-9]/.test(pwd);
  return {
    isValid: minLength && hasUpper && hasNumber,
    minLength: minLength,
    hasUpper: hasUpper,
    hasNumber: hasNumber
  };
}

function validateRequired(value) {
  return value !== null && value !== undefined && String(value).trim().length > 0;
}

function validateURL(url) {
  if (!url || url.trim() === '') return true; // Optional field
  try {
    const parsed = new URL(url);
    return parsed.protocol === 'http:' || parsed.protocol === 'https:';
  } catch (e) {
    return false;
  }
}

function validateMinLength(value, min) {
  return String(value || '').trim().length >= min;
}

// UI Feedback helpers
function showFieldError(fieldId, message) {
  const field = document.getElementById(fieldId);
  if (!field) return;

  field.classList.add('is-invalid');
  field.classList.remove('is-valid');

  let feedback = field.parentElement.querySelector('.invalid-feedback');
  if (!feedback) {
    feedback = document.createElement('div');
    feedback.className = 'invalid-feedback text-danger mt-1 small';
    field.parentElement.appendChild(feedback);
  }
  feedback.textContent = message;
  feedback.style.display = 'block';
}

function clearFieldError(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return;

  field.classList.remove('is-invalid');
  field.classList.add('is-valid');

  const feedback = field.parentElement.querySelector('.invalid-feedback');
  if (feedback) {
    feedback.style.display = 'none';
  }
}

// Specific Form Validations
function validateRegisterForm() {
  let isValid = true;
  const username = document.getElementById('reg_username');
  const email = document.getElementById('reg_email');
  const password = document.getElementById('reg_password');
  const confirmPassword = document.getElementById('reg_confirm_password');

  // Username
  if (username) {
    const val = username.value.trim();
    if (!validateRequired(val)) {
      showFieldError('reg_username', 'Username is required.');
      isValid = false;
    } else if (val.length < 3 || val.length > 50) {
      showFieldError('reg_username', 'Username must be between 3 and 50 characters.');
      isValid = false;
    } else if (!/^[a-zA-Z0-9_]+$/.test(val)) {
      showFieldError('reg_username', 'Username may only contain letters, numbers, and underscores.');
      isValid = false;
    } else {
      clearFieldError('reg_username');
    }
  }

  // Email
  if (email) {
    const val = email.value.trim();
    if (!validateRequired(val)) {
      showFieldError('reg_email', 'Email address is required.');
      isValid = false;
    } else if (!validateEmail(val)) {
      showFieldError('reg_email', 'Please provide a valid email address (e.g. name@domain.com).');
      isValid = false;
    } else {
      clearFieldError('reg_email');
    }
  }

  // Password
  if (password) {
    const val = password.value;
    const check = validatePassword(val);
    if (!validateRequired(val)) {
      showFieldError('reg_password', 'Password is required.');
      isValid = false;
    } else if (!check.isValid) {
      let msg = 'Password must ';
      const missing = [];
      if (!check.minLength) missing.push('be at least 8 characters');
      if (!check.hasUpper) missing.push('have 1 uppercase letter');
      if (!check.hasNumber) missing.push('have 1 number');
      msg += missing.join(', ') + '.';
      showFieldError('reg_password', msg);
      isValid = false;
    } else {
      clearFieldError('reg_password');
    }
  }

  // Confirm Password
  if (confirmPassword && password) {
    const cVal = confirmPassword.value;
    const pVal = password.value;
    if (!validateRequired(cVal)) {
      showFieldError('reg_confirm_password', 'Please confirm your password.');
      isValid = false;
    } else if (cVal !== pVal) {
      showFieldError('reg_confirm_password', 'Passwords do not match.');
      isValid = false;
    } else {
      clearFieldError('reg_confirm_password');
    }
  }

  return isValid;
}

function validateLoginForm() {
  let isValid = true;
  const email = document.getElementById('login_email');
  const password = document.getElementById('login_password');

  if (email) {
    const val = email.value.trim();
    if (!validateRequired(val)) {
      showFieldError('login_email', 'Email is required.');
      isValid = false;
    } else if (!validateEmail(val)) {
      showFieldError('login_email', 'Please enter a valid email address.');
      isValid = false;
    } else {
      clearFieldError('login_email');
    }
  }

  if (password) {
    const val = password.value;
    if (!validateRequired(val)) {
      showFieldError('login_password', 'Password is required.');
      isValid = false;
    } else {
      clearFieldError('login_password');
    }
  }

  return isValid;
}

function validateContactForm() {
  let isValid = true;
  const name = document.getElementById('contact_name');
  const email = document.getElementById('contact_email');
  const message = document.getElementById('contact_message');

  if (name) {
    const val = name.value.trim();
    if (!validateRequired(val)) {
      showFieldError('contact_name', 'Your name is required.');
      isValid = false;
    } else if (val.length < 2) {
      showFieldError('contact_name', 'Name must be at least 2 characters.');
      isValid = false;
    } else {
      clearFieldError('contact_name');
    }
  }

  if (email) {
    const val = email.value.trim();
    if (!validateRequired(val)) {
      showFieldError('contact_email', 'Your email is required.');
      isValid = false;
    } else if (!validateEmail(val)) {
      showFieldError('contact_email', 'Please enter a valid email address.');
      isValid = false;
    } else {
      clearFieldError('contact_email');
    }
  }

  if (message) {
    const val = message.value.trim();
    if (!validateRequired(val)) {
      showFieldError('contact_message', 'Message cannot be empty.');
      isValid = false;
    } else if (val.length < 10) {
      showFieldError('contact_message', 'Please write at least 10 characters.');
      isValid = false;
    } else {
      clearFieldError('contact_message');
    }
  }

  return isValid;
}

function validateRecipeForm() {
  let isValid = true;
  const title = document.getElementById('recipe_title');
  const category = document.getElementById('recipe_category');
  const ingredients = document.getElementById('recipe_ingredients');
  const instructions = document.getElementById('recipe_instructions');
  const cookingTime = document.getElementById('recipe_cooking_time');
  const servings = document.getElementById('recipe_servings');
  const imageUrl = document.getElementById('recipe_image_url');

  if (title) {
    if (!validateRequired(title.value)) {
      showFieldError('recipe_title', 'Recipe title is required.');
      isValid = false;
    } else {
      clearFieldError('recipe_title');
    }
  }

  if (category) {
    if (!validateRequired(category.value)) {
      showFieldError('recipe_category', 'Please select a category.');
      isValid = false;
    } else {
      clearFieldError('recipe_category');
    }
  }

  if (ingredients) {
    if (!validateRequired(ingredients.value)) {
      showFieldError('recipe_ingredients', 'Please provide ingredients list.');
      isValid = false;
    } else {
      clearFieldError('recipe_ingredients');
    }
  }

  if (instructions) {
    if (!validateRequired(instructions.value)) {
      showFieldError('recipe_instructions', 'Preparation instructions are required.');
      isValid = false;
    } else if (instructions.value.trim().length < 20) {
      showFieldError('recipe_instructions', 'Instructions must contain at least 20 characters.');
      isValid = false;
    } else {
      clearFieldError('recipe_instructions');
    }
  }

  if (cookingTime) {
    const val = parseInt(cookingTime.value, 10);
    if (isNaN(val) || val <= 0) {
      showFieldError('recipe_cooking_time', 'Enter valid cooking time in minutes.');
      isValid = false;
    } else {
      clearFieldError('recipe_cooking_time');
    }
  }

  if (servings) {
    const val = parseInt(servings.value, 10);
    if (isNaN(val) || val <= 0) {
      showFieldError('recipe_servings', 'Enter valid number of servings.');
      isValid = false;
    } else {
      clearFieldError('recipe_servings');
    }
  }

  if (imageUrl && imageUrl.value.trim() !== '') {
    if (!validateURL(imageUrl.value.trim())) {
      showFieldError('recipe_image_url', 'Please enter a valid HTTP or HTTPS image URL.');
      isValid = false;
    } else {
      clearFieldError('recipe_image_url');
    }
  }

  return isValid;
}

// Attach input listeners for live validation
document.addEventListener('DOMContentLoaded', function () {
  // Registration form live input listeners
  const regInputs = ['reg_username', 'reg_email', 'reg_password', 'reg_confirm_password'];
  regInputs.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', () => {
        if (el.classList.contains('is-invalid')) {
          validateRegisterForm();
        }
      });
    }
  });

  // Password live meter
  const regPassword = document.getElementById('reg_password');
  const meterBar = document.getElementById('password_strength_bar');
  const meterText = document.getElementById('password_strength_text');
  if (regPassword && meterBar && meterText) {
    regPassword.addEventListener('input', function () {
      const val = this.value;
      let score = 0;
      if (val.length >= 8) score += 35;
      if (/[A-Z]/.test(val)) score += 30;
      if (/[0-9]/.test(val)) score += 20;
      if (/[^A-Za-z0-9]/.test(val)) score += 15;

      meterBar.style.width = score + '%';
      if (score < 40) {
        meterBar.className = 'progress-bar bg-danger';
        meterText.textContent = 'Weak password';
        meterText.className = 'small text-danger';
      } else if (score < 75) {
        meterBar.className = 'progress-bar bg-warning';
        meterText.textContent = 'Medium strength';
        meterText.className = 'small text-warning';
      } else {
        meterBar.className = 'progress-bar bg-success';
        meterText.textContent = 'Strong password';
        meterText.className = 'small text-success';
      }
    });
  }

  // Character counter for textareas
  const textareasWithCounter = document.querySelectorAll('textarea[data-counter]');
  textareasWithCounter.forEach(ta => {
    const counterId = ta.getAttribute('data-counter');
    const counterEl = document.getElementById(counterId);
    if (counterEl) {
      ta.addEventListener('input', () => {
        counterEl.textContent = `${ta.value.length} characters`;
      });
    }
  });
});
