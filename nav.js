// 1. Profile Menu Toggle
function toggleMenu(event) {
    if (event) event.stopPropagation();
    var profileDropdown = document.getElementById("myDropdown");
    var inboxDropdown = document.getElementById("inbox-dropdown");
    var langMenu = document.getElementById("langDropdown");

    // Inbox yoo dammaqee jiraate cufi
    if (inboxDropdown && inboxDropdown.classList.contains("show-inbox")) {
        inboxDropdown.classList.remove("show-inbox");
    }
    
    // Language menu yoo banamee jiraate cufi (yeroo profile cufamu akka cufamuuf)
    if (langMenu && langMenu.classList.contains("show-lang")) {
        langMenu.classList.remove("show-lang");
    }

    if (profileDropdown) {
        profileDropdown.classList.toggle("show");
    }
}

// 2. Inbox Dropdown Toggle
function toggleInbox(event) {
    if (event) event.stopPropagation();
    var inboxDropdown = document.getElementById("inbox-dropdown");
    var profileDropdown = document.getElementById("myDropdown");

    if (profileDropdown && profileDropdown.classList.contains("show")) {
        profileDropdown.classList.remove("show");
    }

    if (inboxDropdown) {
        inboxDropdown.classList.toggle("show-inbox");
    }
}

// 3. Language Sub-menu Toggle (Nested)
function toggleLangMenu(event) {
    event.stopPropagation(); // Profile menu akka hin cufamne
    var langMenu = document.getElementById("langDropdown");
    var arrow = document.getElementById("lang-arrow");

    // Menu banuu fi cufuu
    if (langMenu.classList.contains("show-lang")) {
        langMenu.classList.remove("show-lang");
        arrow.innerHTML = "▾"; // Gadi agarsiisi
    } else {
        langMenu.classList.add("show-lang");
        arrow.innerHTML = "▴"; // Ol agarsiisi
    }
}

// 4. Alatti yoo cuqaasan Dropdown-oota hunda cufuu (window.onclick TOKKO QOFA)
window.onclick = function(event) {
    
    // A. Profile Menu fi Language Menu cufuu
    // Profile menu keessas yoo ta'e Language menu yoo ta'e hin cufin
    if (!event.target.closest('.user-profile')) {
        var profileDropdowns = document.getElementsByClassName("dropdown-menu");
        var langDropdowns = document.getElementsByClassName("lang-submenu");

        for (var i = 0; i < profileDropdowns.length; i++) {
            profileDropdowns[i].classList.remove('show');
        }
        for (var j = 0; j < langDropdowns.length; j++) {
            langDropdowns[j].classList.remove('show-lang');
        }
    } else {
        // Yoo profile menu keessa cuqaaste garuu Language menu ala yoo ta'e lang menu qofa cufi
        if (!event.target.closest('.nested-dropdown')) {
            var langMenus = document.getElementsByClassName("lang-submenu");
            for (var k = 0; k < langMenus.length; k++) {
                langMenus[k].classList.remove('show-lang');
            }
        }
    }
    
    // B. Inbox Dropdown cufuu
    if (!event.target.closest('.message-icon') && !event.target.closest('.inbox-dropdown')) {
        var inboxDropdowns = document.getElementsByClassName("inbox-dropdown");
        for (var l = 0; l < inboxDropdowns.length; l++) {
            inboxDropdowns[l].classList.remove('show-inbox');
        }
    }
}