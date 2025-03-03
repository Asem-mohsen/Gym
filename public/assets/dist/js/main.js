$(function () {
    "use strict";

        // :: 12.0 PreventDefault a Click
    $("a[href='#']").on('click', function ($) {
        $.preventDefault();
    });

    $('.nav-item').each(function() {
        if ($(this).find('.nav-link.active').length > 0) {
            $(this).addClass('menu-open');
        }
    });

});

/*
**
    This function check all the disabled inputs, add the
    disabled attribute if the user deleted it manually
**
*/
function monitorDisabledInputs() {
    const disabledInputs = document.querySelectorAll('input[disabled], textarea[disabled]');

    disabledInputs.forEach(input => {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'disabled') {
                    if (!input.disabled) {
                        // Re-add the disabled attribute
                        input.disabled = true;

                        // Trigger a change event
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                }
            });
        });

        // Observe changes to the attributes of each input
        observer.observe(input, {
            attributes: true
        });
    });
}

// Monitor initially disabled inputs
monitorDisabledInputs();

// Re-monitor inputs if new inputs are added dynamically to the DOM
const bodyObserver = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        if (mutation.type === 'childList' || mutation.type === 'attributes') {
            monitorDisabledInputs();
        }
    });
});

// Start observing the entire body for changes
bodyObserver.observe(document.body, {
    childList: true,
    attributes: true,
    subtree: true
});


function generateLoremIpsum(wordCount, elementId) {
    const loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. " +
        "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. " +
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. " +
        "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. " +
        "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

    const words = loremIpsum.split(' ');
    let result = [];
    while (result.length < wordCount) {
        result = result.concat(words);
    }
    result = result.slice(0, wordCount).join(' ');

    const targetElement = document.getElementById(elementId);
    if (targetElement) {
        targetElement.value = result;
    }
}
function generateArabicLoremIpsum(wordCount, elementId) {
    const loremIpsum = "لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيتدو أيوسمود" +
                "تيبوري أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا. يوت انيم أد مينيم فينايم,كيواس" +
                " يللامكو لابوريس نسييوت اليكيويب ايكس ايي كوميدو كونسيكيوات.دوي ايسمود" ;

    const words = loremIpsum.split(' ');
    let result = [];
    while (result.length < wordCount) {
        result = result.concat(words);
    }
    result = result.slice(0, wordCount).join(' ');

    const targetElement = document.getElementById(elementId);
    if (targetElement) {
        targetElement.value = result;
    }
}


// Function to set Remaining charachter length
function updateCharacterCount(textareaId, maxLength) {
    const textarea = $('#' + textareaId);
    const remaining = $('.remaining[data-for="' + textareaId + '"]');
    const length = textarea.val().length;

    if (length > maxLength) {
        textarea.val(textarea.val().substring(0, maxLength));
        remaining.text('0 Characters Remaining').css('color', 'red');
    } else {
        remaining.text((maxLength - length) + ' Characters Remaining').css('color', '');
    }
}
