let burgerIcon = document.querySelector(".burger")
let popupBurger = document.querySelector(".menu-burger")
let closeBurger = document.querySelector(".croixCloseBurger")
let closeLangue = document.querySelector(".croixCloseLangue")
let langue = document.querySelector(".langue")
let popupLangue = document.querySelector(".selectLangue")
let bar = document.getElementById("progress-bar") || null
let pseudoProfilInput = document.getElementById("nickname_pseudonyme") || null
let pseudoProfil = pseudoProfilInput.value || null
var newPseudo = document.getElementById("nickname_pseudonyme") || null
const editBtn = document.querySelector(".btnEdit") || null


if(pseudoProfil && newPseudo && editBtn)
{
    newPseudo.addEventListener("change", () => {
        
        if(newPseudo.value == pseudoProfil)
        {
            editBtn.setAttribute("disabled", "");
            editBtn.classList.add("disabled");
        }
        else
        {
            editBtn.removeAttribute("disabled", "");
            editBtn.classList.remove("disabled");
        }
    })
}




// Ces 4 EventListeners servent à faire apparaitre et disparaitre les menu burger et de changement de langue
burgerIcon.addEventListener("click", () => {
    popupBurger.classList.toggle("letVisibleBurger")
})

closeBurger.addEventListener("click", () => {
    popupBurger.classList.toggle("letVisibleBurger")
})

langue.addEventListener("click", () => {
    popupLangue.classList.toggle("letVisibleLangue")
})

closeLangue.addEventListener("click", () => {
    popupLangue.classList.toggle("letVisibleLangue")
})


// Cette fonction sert à faire progresser la progressebar quand on scroll sur la page
function myFunction() {
    if(bar)
    {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        bar.style.width = scrolled + "%"; 
    }
    else
    {
        return null
    }

}
window.onscroll = function() {myFunction()};