let burgerIcon = document.querySelector(".burger")
let popupBurger = document.querySelector(".menu-burger")
let closeBurger = document.querySelector(".croixCloseBurger")
let closeLangue = document.querySelector(".croixCloseLangue")
let langue = document.querySelector(".langue")
let popupLangue = document.querySelector(".selectLangue")
let bar = document.getElementById("progress-bar") || null
let pseudoProfilInput = document.getElementById("nickname_pseudonyme") || null
var newPseudo = document.getElementById("nickname_pseudonyme") || null
const editBtn = document.querySelector(".btnEdit") || null
let btnAddflash = document.querySelector(".btnAddFlash") || null
let popupAddFlash = document.querySelector(".popupAddFlash") || null
let body = document.querySelector("body")
let closePopupFlash = document.querySelector(".croixPopupFlash")
let closePopupFlash2 = document.querySelector(".croixFlash2")
const flashs = document.getElementsByClassName('flash')
let popupDetailFlash = document.querySelector(".popupDetailFlash") || null
let detImage = document.querySelector(".detImage") || null




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

if(pseudoProfilInput && newPseudo && editBtn)
{
    let pseudoProfil = pseudoProfilInput.value || null
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


if(popupAddFlash && btnAddflash)
{
    btnAddflash.addEventListener("click", () => {
        popupAddFlash.classList.remove("hidden")
        popupAddFlash.showModal()
        body.classList.add("disableScroll")
        if(popupAddFlash.open)
        {
            addEventListener("keydown", (e) => {
                if(e.key == "Escape")
                {
                    popupAddFlash.classList.add("hidden")
                    body.classList.remove("disableScroll")
                    return;
                }
            })
            closePopupFlash.addEventListener("click", () => {
                popupAddFlash.close()
                body.classList.remove("disableScroll")
                return;
            })
        }
    });
}


for (let flash of flashs) {
    flash.addEventListener('click', () => {

        let img = flash.getAttribute("src")
        let alt = flash.getAttribute("alt")

        detImage.setAttribute("src", img)
        detImage.setAttribute("alt", alt)

        closePopupFlash2.classList.add("croixPopupFlash2")
        popupDetailFlash.classList.remove("hidden")
        popupDetailFlash.showModal()
        body.classList.add("disableScroll")
        if(popupDetailFlash.open)
        {
            addEventListener("keydown", (e) => {
                if(e.key == "Escape")
                {
                    popupDetailFlash.classList.add("hidden")
                    body.classList.remove("disableScroll")
                    closePopupFlash2.classList.remove("croixPopupFlash2")
                    detImage.setAttribute("alt", "")
                    detImage.setAttribute("src", "")
                    return;
                }
            })
                closePopupFlash2.addEventListener("click", () => {
                    console.log("test")
                    popupDetailFlash.close()
                    popupDetailFlash.classList.add("hidden")
                    detImage.setAttribute("alt", "")
                    detImage.setAttribute("src", "")

                    closePopupFlash2.classList.remove("croixPopupFlash2")
                    body.classList.remove("disableScroll")
                    return;
                })
        }
    })
}

