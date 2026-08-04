(function () {
	const utmFields = [
		'utm_source',
		'utm_medium',
		'utm_campaign',
		'utm_term',
		'utm_content',
	];

	function processUtmFields() {
		const params = new URLSearchParams(window.location.search);

		utmFields.forEach(function (fieldName) {
			document
				.querySelectorAll(
					'.hs-form-html input[name="0-1/' + fieldName + '"]'
				)
				.forEach(function (input) {
					const urlValue = params.get(fieldName);

					if (urlValue !== null) {
						const valueSetter = Object.getOwnPropertyDescriptor(
							HTMLInputElement.prototype,
							'value'
						).set;
						valueSetter.call(input, urlValue);
						input.dispatchEvent(
							new Event('input', { bubbles: true })
						);
						input.dispatchEvent(
							new Event('change', { bubbles: true })
						);
					}

					const row = input.closest('.hsfc-Row');
					if (row) {
						row.style.setProperty(
							'display',
							'none',
							'important'
						);
					}
				});
		});
	}

	const observer = new MutationObserver(processUtmFields);
	observer.observe(document.body, { childList: true, subtree: true });
	processUtmFields();
})();
