// SIGNUP FUNCTION
function justValidate() {
	// let emailAvailable;

	$(function () {
		const validation = new JustValidate("#signup-form", {
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});

		validation
			.addField(
				"#mail",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						rule: "email",
						errorMessage: "Invalid email format",
					},
					{
						validator: function (value) {
							let emailAvailable = false;
							$.ajax({
								url: "user/email_available",
								type: "POST",
								async: false,
								data: { email: value },
								dataType: "text",

								success: (response) => {
									// Convert the response to boolean
									//RETURNS TRUE IF EMAIL EXISTS ALREADY
									emailAvailable = response.trim() === "true";
								},
							});
							return emailAvailable;
						},
						errorMessage: "Mail already exists",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#full_name",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						rule: "minLength",
						value: 3,
						errorMessage: "Full Name must be at least 3 characters",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#iban",
				[
					{
						rule: "customRegexp",
						value: /^[a-zA-Z]{2}\d{2}\s(\d{4}\s)+\d{4}$/,
						errorMessage: "Invalid IBAN format",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#password",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						rule: "minLength",
						value: 8,
						errorMessage: "Minimum 8 characters",
					},
					{
						rule: "maxLength",
						value: 16,
						errorMessage: "Maximum 16 characters",
					},
					{
						rule: "customRegexp",
						value: /[A-Z]/,
						errorMessage: "Password must contain an uppercase letter",
					},
					{
						rule: "customRegexp",
						value: /\d/,
						errorMessage: "Password must contain a digit",
					},
					{
						rule: "customRegexp",
						value: /['";:,.\/?\\-]/,
						errorMessage: "Password must contain a special character",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#password_confirm",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						validator: function (value, fields) {
							if (fields["#password"] && fields["#password"].elem) {
								const repeatPasswordValue = fields["#password"].elem.value;
								return value === repeatPasswordValue;
							}
							return true;
						},
						errorMessage: "Passwords should be the same",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.onSuccess(function (event) {
				event.preventDefault(); // prevent the form default submit event

				const formData = $("#signup-form").serialize(); // serialize the form data

				$.ajax({
					url: "main/signup", // the URL where to submit
					type: "POST",
					data: formData,
					success: function (response) {
						// handle successful form submission here
						console.log("Success: The form was submitted correctly.");
						// redirect the happy user
						window.location.href = " profile/profile";
					},
					error: function (errorThrown) {
						// handle errors here
						console.log(
							"Error: An error occurred while submitting the form:",
							errorThrown
						);
						alert(
							"Oops! An error occurred while processing your registration. Please try again."
						);
					},
				});
			});

		$("input:text:first").focus();
	});
}

// LOGIN

function JVLogin() {
	$(function () {
		const validation = new JustValidate("#login-form", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
			serializeFormData: false,
		});

		validation.addField(
			"#mail",
			[
				{
					rule: "required",
					errorMessage: "Field is required",
				},
				{
					rule: "email",
					errorMessage: "Invalid email format",
				},
				{
					validator: function (value) {
						let emailExists = false;
						$.ajax({
							url: "user/check_email_service",
							type: "POST",
							async: false, //call synchronous
							data: { email: value },
							dataType: "text",

							success: (response) => {
								// Convert the response string to a boolean
								emailExists = response.trim() === "false";
							},
						});
						return emailExists;
					},
					errorMessage: "No user found with this email",
				},
			],
			{ successMessage: "Looks good !" }
		);

		validation.addField(
            "#password",
            [
                {
                    rule: "required",
                    errorMessage: "Field is required",
                },
                {
                    validator: function (value) {
                        let passwordIsValid = false;
                        $.ajax({
                            url: "user/check_password_service",
                            type: "POST",
                            async: false, //call synchronous
                            data: {
                                email: $("#mail").val(),
                                password: value,
                            },
                            dataType: "json",

                            success: (response) => {
                                passwordIsValid = response.success;
                            },
                        });
                        return passwordIsValid;
                    },
                    errorMessage: "Wrong password",
                },
            ],
            { successMessage: "Looks good !" }
        );

		validation.onSuccess(function (event) {
			event.target.submit();
		});

		$("input:text:first").focus();
	});
}

//ADD OPERATION
function JVAddOperation() {
	function glowInput(selector, glowColor) {
		const input = document.querySelector(selector);
		input.style.borderColor = glowColor;
		input.style.boxShadow = `0 0 5px ${glowColor}`;
	}

	$(function () {
		const validation = new JustValidate("#add-exp-form", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
			saveTemplateCheckbox: "#save",
		});

		validation
			.addField("#title", [
				{
					rule: "required",
					errorMessage: "Le titre est obligatoire",
				},
				{
					rule: "minLength",
					value: 3,
					errorMessage: "Title must be at least 3 characters",
				},
				{
					rule: "maxLength",
					value: 256,
					errorMessage: "Title must be at max 256 characters",
				},
			])

			.addField("#amount", [
				{
					rule: "required",
					errorMessage: "Le montant est obligatoire",
				},
				{
					rule: "minNumber",
					value: 0.01,
					errorMessage:
						"Le montant doit être supérieur ou égal à un centime d'euro",
				},
			]);

		validation.addField("#operation_date", [
			{
				rule: "required",
				errorMessage: "La date est obligatoire",
				onFail: () => glowInput("#operation_date", "red"),
				onSuccess: () => glowInput("#operation_date", "limegreen"),
			},
			{
				validator: function (value) {
					const today = new Date();
					const inputDate = new Date(value);
					const isDateInFuture = inputDate > today;
					return !isDateInFuture;
				},
				errorMessage: "La date ne peut pas être dans le futur",
				onFail: () => glowInput("#operation_date", "red"),
				onSuccess: () => glowInput("#operation_date", "limegreen"),
			},
		]);

		validation.addField("#savename", [
			{
				validator: function (value) {
					let templateNameAvailable = true;  //template name is available until proven otherwise
					const tricId = document.querySelector("#tricId").value;
					$.ajax({
						url: `templates/validateTemplateNameForIt3/`,
						type: "POST",
						async: false,  //request synchronous
						data: { 
							template_name: value, 
							tricId: tricId 
						},
						dataType: "json",
						success: (data) => {
							// If the template name is not available, set templateNameAvailable to false
							templateNameAvailable = data.isAvailable;
						},
						error: (error) => {
							console.error("There was an error!", error);
						},
					});
					return templateNameAvailable;
				},
				errorMessage: "This name is already taken. Please choose a different name.",
			},

			{
				rule: "maxLength",
				value: 256,
				errorMessage: "Template name must be at max 256 characters",
			},
			{
				rule: "minLength",
				value: 3,
				errorMessage: "Template name must be at min 3 characters",
			},
		]);

		validation.onValidate().onSuccess(function () {
			document.querySelector("#add-exp-form").submit();
		});

		$("input:text:first").focus();
	});

	function validateCheckboxes() {
		const checkboxes = document.querySelectorAll('[id$="_userCheckbox"]');
		const weightInputs = document.querySelectorAll("#userWeight");
		const amountInputs = document.querySelectorAll('[id$="_amount"]');
		const saveCheckbox = document.querySelector("#save");
		const repartitionTemplate = document.querySelector("#repartitionTemplate");
		const selectedOption =
			repartitionTemplate.options[repartitionTemplate.selectedIndex].value;

		// If the 'save' checkbox is not checked or an option from the dropdown is selected, return true.
		if (!saveCheckbox.checked || selectedOption !== "option-default") {
			return true;
		}

		let atLeastOneChecked = false;
		let sumOfWeights = 0;

		checkboxes.forEach((checkbox, index) => {
			if (checkbox.checked) {
				atLeastOneChecked = true;
				sumOfWeights += parseInt(weightInputs[index].value);
			}
		});

		if (!atLeastOneChecked || sumOfWeights <= 0) {
			// Show custom error message and apply glowing red border
			weightInputs.forEach((input) => {
				input.style.borderColor = "red";
				input.style.boxShadow = "0 0 5px red";
			});
			amountInputs.forEach((input) => {
				input.style.borderColor = "red";
				input.style.boxShadow = "0 0 5px red";
			});
			return false;
		} else {
			// Hide custom error message and apply glowing green border
			weightInputs.forEach((input) => {
				input.style.borderColor = "limegreen";
				input.style.boxShadow = "0 0 5px limegreen";
			});
			amountInputs.forEach((input) => {
				input.style.borderColor = "limegreen";
				input.style.boxShadow = "0 0 5px limegreen";
			});
			return true;
		}
	}

	// Add event listeners to checkboxes and weight inputs
	const checkboxes = document.querySelectorAll('[id$="_userCheckbox"]');
	const weightInputs = document.querySelectorAll("#userWeight");

	checkboxes.forEach((checkbox) => {
		checkbox.addEventListener("change", () => {
			validateCheckboxes();
		});
	});

	weightInputs.forEach((weightInput) => {
		weightInput.addEventListener("input", () => {
			validateCheckboxes();
		});
	});

	// Add custom validation to the form submission
	const form = document.getElementById("add-exp-form");
	if (form) {
		form.addEventListener("submit", (event) => {
			const saveCheckbox = document.querySelector("#save");

			// If the 'save' checkbox is not checked, submit the form.
			if (!saveCheckbox.checked) {
				return;
			}

			// If the 'save' checkbox is checked, validate the checkboxes.
			if (!validateCheckboxes()) {
				event.preventDefault();
				// You can display an error message here if needed
			} else {
				// Form is valid and will be submitted
			}
		});
	}
}

//EDIT PROFILE
function JVEditProfile() {
	function debounce(func, wait) {
		let timeout;
		return function () {
			clearTimeout(timeout);
			timeout = setTimeout(() => func.apply(this, arguments), wait);
		};
	}

	$(function () {
		const validation = new JustValidate("#edit_profile", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});

		validation
			.addField(
				"#mail",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						rule: "email",
						errorMessage: "Invalid email format",
					},
					{
						validator: function (value) {
							let emailExists = false;
							$.ajax({
								url: "user/check_edit_prf_email",
								type: "POST",
								async: false, //call synchronous
								data: { email: value },
								dataType: "text",
								success: (response) => {
									emailExists = response.trim() === "true";
								},
								error: function (errorThrown) {
									console.log("Email check failed: " + errorThrown);
								},
							});
							return !emailExists;
						},
						errorMessage: "Email is already in use",
					}
					
					,
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#fullName",
				[
					{
						rule: "required",
						errorMessage: "Field is required",
					},
					{
						rule: "minLength",
						value: 3,
						errorMessage: "Full Name must be at least 3 characters",
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#iban",
				[
					{
						rule: "customRegexp",
						value: /^[BE]+\d\d(\s([0-9]+\s)+)\d\d\d\d$/,
						errorMessage: "Invalid IBAN format",
					},
				],
				{ successMessage: "Looks good !" }
			);

		const debouncedValidation = debounce(() => validation.validate(), 300);
		$("#edit_profile").on("input", debouncedValidation);
		validation.onSuccess(function (event) {
			event.target.submit();
		});
	});
}

//CHANGE PASSWORD
function JVChangePassword() {
	$(document).ready(function () {

		const chpassForm = new JustValidate(".chpass-form", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});

		async function checkCurrentPassword(password) {
			try {
				const user = JSON.parse($("input[name='user']").val());

				const response = await $.post("user/checkUserPass/", {
					currentPassword: password,
					user: user,
				});
				const result = JSON.parse(response);
				return result.isPasswordCorrect;
			} catch (error) {
				console.error("Error checking current password:", error);
				return false;
			}
		}

		chpassForm.addField("#currentPassword", [
			{
				rule: "required",
				errorMessage: "Field is required",
			},
			{
				validator: async function (value) {
					const isPasswordCorrect = await checkCurrentPassword(value, user);
					return isPasswordCorrect;
				},

				errorMessage: "Current password is incorrect",
			},
			{
				rule: "minLength",
				value: 8,
				errorMessage: "Minimum 8 characters",
			},
			{
				rule: "maxLength",
				value: 16,
				errorMessage: "Maximum 16 characters",
			},
			{
				rule: "customRegexp",
				value: /[A-Z]/,
				errorMessage: "Password must contain an uppercase letter",
			},
			{
				rule: "customRegexp",
				value: /\d/,
				errorMessage: "Password must contain a digit",
			},
			{
				rule: "customRegexp",
				value: /['";:,.\/?\\-]/,
				errorMessage: "Password must contain a special character",
			},
		]);

		chpassForm.addField("#newPassword", [
			{
				rule: "required",
				errorMessage: "Field is required",
			},
			{
				rule: "minLength",
				value: 8,
				errorMessage: "Minimum 8 characters",
			},
			{
				rule: "maxLength",
				value: 16,
				errorMessage: "Maximum 16 characters",
			},
			{
				rule: "customRegexp",
				value: /[A-Z]/,
				errorMessage: "Password must contain an uppercase letter",
			},
			{
				rule: "customRegexp",
				value: /\d/,
				errorMessage: "Password must contain a digit",
			},
			{
				rule: "customRegexp",
				value: /['";:,.\/?\\-]/,
				errorMessage: "Password must contain a special character",
			},
		]);

		chpassForm.addField("#confirmPassword", [
			{
				rule: "required",
				errorMessage: "Field is required",
			},
			{
				validator: function (value, fields) {
					if (fields["#newPassword"] && fields["#newPassword"].elem) {
						const newPasswordValue = fields["#newPassword"].elem.value;
						return value === newPasswordValue;
					}
					return true;
				},
				errorMessage: "Passwords should be the same",
			},
		]);

		chpassForm.onSuccess(function (event) {
			event.target.submit();
		});

		$("input:text:first").focus();
	});
}

//ADD TRICOUNT

function JVAddTricount() {
	function debounce(func, wait) {
		let timeout;
		return function () {
			clearTimeout(timeout);
			timeout = setTimeout(() => func.apply(this, arguments), wait);
		};
	}

	$(function () {
		const validation = new JustValidate("#addTricount", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});

		validation
			.addField(
				"#text4b",
				[
					{
						rule: "minLength",
						value: 3,
						errorMessage: "Title must have at least 3 characters",
						customValidation: (value, resolve) => {
							validateTitle(value, function (isTitleValid) {
								if (!isTitleValid) {
									resolve({
										isValid: false,
										errorMessage: "Title must be unique for the creator",
									});
								} else {
									resolve({ isValid: true });
								}
							});
						},
					},
				],
				{ successMessage: "Looks good !" }
			)

			.addField(
				"#textarea4b",
				[
					{
						rule: "minLength",
						value: 3,
						errorMessage:
							"Description must have at least 3 characters if provided",
						isOptional: true,
					},
				],
				{ successMessage: "Looks good !" }
			);

		const debouncedValidation = debounce(() => validation.validate(), 300);
		$("#addTricount").on("input", debouncedValidation);
	});
}

//EDIT TRICOUNT
function JVEditTricount() {
	function debounce(func, wait) {
		let timeout;
		return function () {
			clearTimeout(timeout);
			timeout = setTimeout(() => func.apply(this, arguments), wait);
		};
	}

	$(function () {
		const validation = new JustValidate("#updateTricount", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});
	
		validation.addField(".tricountTitle", [
			{
				validator: function (value) {
					let titleUnique = true; // assume title is unique until proven otherwise
					$.ajax({
						url: `tricount/check_title/`, // the URL might be different, adjust as needed
						type: "POST",
						async: false,
						data: { 
							title: value, 
							tricId: document.querySelector("#tricId").value 
						},						
						dataType: "json",
						success: (data) => {
							titleUnique = data.isUnique;
						},
						error: (error) => {
							console.error("There was an error!", error);
						},
					});
					return titleUnique;
				},
				errorMessage: "Title must be unique for the creator",
			},
			{
				rule: "required",
				errorMessage: "Title is required",
			},
			{
				rule: "minLength",
				value: 3,
				errorMessage: "Title must have at least 3 characters",
			},
			{
				rule: "maxLength",
				value: 256,
				errorMessage: "Title must be at max 256 characters",
			},
		]);
	
		validation.addField("#description", [
			{
				rule: "minLength",
				value: 3,
				errorMessage: "Description must have at least 3 characters if provided",
				isOptional: true,
			},
		]);
		
		const form = document.getElementById("updateTricount");
		form.addEventListener("submit", (event) => {
			event.preventDefault(); // Prevent the default form submission
			form.submit(); // Submit the form
		});
	});
	
}
//EDIT TEMPLATE
function JVEditTemplate() {

	function setGlowingBorder(elements, color) {
		elements.forEach((element) => {
			const parentDiv = element.closest(".edit_template_items");
			const userTextInput = parentDiv.querySelector('input[name="user"]');

			element.style.boxShadow =
				color === "green" ? "0 0 5px 1px limegreen" : "0 0 5px 1px red";
			userTextInput.style.borderColor = color === "green" ? "limegreen" : "red";
			userTextInput.style.boxShadow =
				color === "green" ? "0 0 5px limegreen" : "0 0 5px red";
		});
	}

	function validateCheckboxes() {
		const checkboxes = document.querySelectorAll(".check");

		let atLeastOneChecked = false;

		checkboxes.forEach((checkbox) => {
			if (checkbox.checked) {
				atLeastOneChecked = true;
			}
		});

		if (!atLeastOneChecked) {
			setGlowingBorder(checkboxes, "red");
			return false;
		} else {
			setGlowingBorder(checkboxes, "green");
			return true;
		}
	}

	const form = document.getElementById("edit_template_form");
	form.addEventListener("submit", (event) => {
		if (!validateCheckboxes()) {
			event.preventDefault();
		}
	});

	const checkboxes = document.querySelectorAll(".check");
	checkboxes.forEach((checkbox) => {
		checkbox.addEventListener("change", () => {
			validateCheckboxes();
		});
	});

	$(function () {
		const validation = new JustValidate("#edit_template_form", {
			errorFieldCssClass: "error-field",
			successFieldCssClass: "success-field",
			defaultStyles: false,
			validateBeforeSubmitting: true,
			lockForm: true,
			focusInvalidField: false,
			successLabelCssClass: ["success"],
			errorLabelCssClass: ["errors"],
		});

		validation.addField("#template_title", [
            {
                validator: function (value) {
                    let templateNameAvailable = true; //template name is available until proven otherwise
                    const tricId = document.querySelector("#tricId").value;
                    $.ajax({
                        url: "templates/validateTemplateNameForIt3/",
                        type: "POST",
                        async: false,  //request synchronous
                        data: { 
                            template_name: value, 
                            tricId: tricId 
                        },
                        dataType: "json",
                        success: (data) => {
                            // If the template name is not available, set templateNameAvailable to false
                            templateNameAvailable = data.isAvailable;
                        },
                        error: (error) => {
                            console.error("There was an error!", error);
                        },
                    });
                    return templateNameAvailable;
                },
                errorMessage: "This name is already taken. Please choose a different name.",
            },
            {
                rule: "required",
                errorMessage: "Title is required",
            },
            {
                rule: "minLength",
                value: 3,
                errorMessage: "Title must be at least 3 characters",
            },
            {
                rule: "maxLength",
                value: 256,
                errorMessage: "Title must be at max 256 characters",
            },
        ]);

		const checkboxes = document.querySelectorAll(".check");
		checkboxes.forEach((checkbox) => {
			checkbox.addEventListener("change", () => {
				validateCheckboxes();
			});
		});

		const form = document.getElementById("edit_template_form");
		form.addEventListener("submit", (event) => {
			if (!validateCheckboxes()) {
				event.preventDefault();
			}else
				form.submit();
		});
	});
}
