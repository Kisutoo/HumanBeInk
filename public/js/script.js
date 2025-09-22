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
let imgInp = document.querySelector(".files") || null
let contactImage = document.querySelector(".previewImage") || null
let current = document.querySelector(".current") || null
let pagination = document.querySelector(".pagination") || null
let flashContainer = document.querySelector(".flashContainer");
let maxPagePagination = flashContainer ? flashContainer.getAttribute("data-maxpages") : null
const categories = document.getElementsByClassName('btnFilter')
let btnShowFilters = document.querySelector(".btnShowFilters") || null
let popupFilters = document.querySelector(".popupFilters") || null
let closePopupFilter = document.querySelector(".croixPopupFilter") || null
const url = new URL(window.location.href);
let params = new URLSearchParams();
let croixDialogsArea = document.querySelector(".editSimuArea") || null
let croixDialogsSize = document.querySelector(".editSimuSize") || null
let croixDialogsColor = document.querySelector(".editSimuColor") || null
let popupColor = document.querySelector(".popupSimuColor") || null
let popupArea = document.querySelector(".popupSimuArea") || null
let popupSize = document.querySelector(".popupSimuSize") || null

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
                popupAddFlash.classList.add("hidden")
                popupAddFlash.close()
                body.classList.remove("disableScroll")
                return;
            })
        }
    });
}

function openClosePopupFilter(params)
{

    btnShowFilters.addEventListener("click", () => {

        body.classList.add("disableScroll");
        popupFilters.classList.remove("hidden");
        popupFilters.showModal();

        addEventListener("keydown", (e) => {
            if(e.key == "Escape")
            {
                popupFilters.classList.add("hidden");
                body.classList.remove("disableScroll");
                return;
            }
        })
        closePopupFilter.addEventListener("click", () => {
        popupFilters.classList.add("hidden");
        popupFilters.close();
        body.classList.remove("disableScroll");
        return;
        })
    })
    for(let category of categories)
    {
        category.addEventListener("click", () => {

            if(category.getAttribute("index") == "0")
            {
                let catId = category.querySelector("#btnFilter").getAttribute("data-id")

                params.append("categories[]", catId)
                category.classList.add("activeFilter");
                category.setAttribute("index", "1");

                return params;
            }
            if(category.getAttribute("index") == "1")
            {
                let catId = category.querySelector("#btnFilter").getAttribute("data-id")

                params.delete("categories[]", catId)
                category.classList.remove("activeFilter");
                category.setAttribute("index", "0");

                return params;
            }

        })
    }
}
if(pagination)
    openClosePopupFilter(params)


function clickFlash()
{
    console.log(popupDetailFlash)
    for (let flash of flashs) {
        flash.addEventListener('click', () => {

            let buttonContact = document.querySelector("#flashButtonContact")
            let buttonDeleteFlash = document.querySelector("#deleteFlashBtn") 
            let img = flash.getAttribute("src")
            let alt = flash.getAttribute("alt")
            let id = flash.getAttribute("index")
            let lienFavFlash = document.querySelector("#lienFavFlash")
            

            if(buttonDeleteFlash)
            {
                buttonDeleteFlash.setAttribute("href", "/deleteFlash/" + id)
            }

            // Sert à récupérer seulement le nom de l'image 
            imageNameArray = img.split("/")
            imageName = imageNameArray[3]

            
            detImage.setAttribute("src", img)
            detImage.setAttribute("alt", alt)
            buttonContact.setAttribute("href", "/contact/" + imageName)

            console.log(popupDetailFlash)
            lienFavFlash.onclick = (e) => {
                e.preventDefault();
                fetch(url.pathname + "/addFav?id=" + id + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
                })
                .then(r => r.text())
                .then(html => {
                    
                    document.querySelector(".dialogContainerDetailFlash").innerHTML = html;
                })
                .catch(e => console.error(e))
                console.log(popupDetailFlash)
                popupDetailFlash.classList.add("hidden")
                popupDetailFlash.close()
                body.classList.remove("disableScroll")
            }
            
            

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
                        buttonContact.setAttribute("href", "")
                        if(buttonDeleteFlash != null)
                        {
                            buttonDeleteFlash.setAttribute("href", "")
                        }

                        return;
                    }
                })
                    closePopupFlash2.addEventListener("click", () => {

                        popupDetailFlash.close()
                        popupDetailFlash.classList.add("hidden")
                        detImage.setAttribute("alt", "")
                        detImage.setAttribute("src", "")
                        buttonContact.setAttribute("href", "")
                        if(buttonDeleteFlash != null)
                        {
                            buttonDeleteFlash.setAttribute("href", "")
                        }
                        
                        closePopupFlash2.classList.remove("croixPopupFlash2")
                        body.classList.remove("disableScroll")

                        return;
                    })
            }
        })
    }
}
if(flashs)
    clickFlash()

if(imgInp != null)
{
    if(contactImage.getAttribute("src") && imgInp.files.length == 0)
    {
        img = contactImage.getAttribute("src")

        imageNameArray = img.split("/")
        imageName = imageNameArray[3]
        
        const dataTransfer = new DataTransfer();
        const myFile = new File(["image"], imageName, {
            type: 'image/webp',
            lastModified: new Date(),
        });
        
        dataTransfer.items.add(myFile);
        imgInp.files = dataTransfer.files;
    }


    imgInp.onchange = evt => {
      const [file] = imgInp.files
      if (file) {
        contactImage.src = URL.createObjectURL(file)
      }
      if(imgInp.files.length == 0)
      {
        contactImage.src = "";
      }
    }
}


function changeCurrentSpanToP(maxPagePagination)
{
    if(maxPagePagination == "1" || maxPagePagination == "0") return;

    let current = document.querySelector(".current") || null

    nbCurrent = current.innerText
    current.innerHTML = "<p class=goldPolice >" + nbCurrent + "</p>"

    if(nbCurrent == "1")
        current.classList.add("first")
    else if(nbCurrent == maxPagePagination)
        current.classList.add("last")
}
if(maxPagePagination)
    changeCurrentSpanToP(maxPagePagination)



function attachPaginationEvents(maxPagePagination, params) {
    // récupère la pagination actuelle

    if(parseInt(maxPagePagination) <= 1) return;

    let pagination = document.querySelector(".pagination");
    let nbPagination = parseInt(document.querySelector(".current").innerText)

    let submitFilter = document.querySelector(".submitFilterBtn")
    

    if (!pagination) return;

    submitFilter.onclick = () => {

        fetch(url.pathname + "?" + params.toString() + "&page=1"  + "&ajax=1", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(r => r.text())
        .then(html => {
            document.querySelector("#flash-container").innerHTML = html
            let flashContainer = document.querySelector(".flashContainer");
            let newMaxPagePagination = flashContainer.getAttribute("data-maxpages")
            attachPaginationEvents(newMaxPagePagination, params);
            changeCurrentSpanToP(newMaxPagePagination)
            clickFlash();
        })
        .catch(e => console.error(e))
        popupFilters.classList.add("hidden");
        popupFilters.close();
        body.classList.remove("disableScroll");
    }

    for (let lienPagination of pagination.querySelectorAll("a")) {
        lienPagination.onclick = (e) => {
            e.preventDefault();
            // ici, on récupères le numéro de page
            let page = 1;
            if (lienPagination.innerText == ">")
                page = nbPagination + 1;
            else if (lienPagination.innerText == "<")
                page = nbPagination - 1;
            else if (lienPagination.innerText == "<<")
                page = 1;
            else if (lienPagination.innerText == ">>")
                page = maxPages;
            else if (!["<", ">", "<<", ">>"].includes(lienPagination.innerText))
                page = parseInt(lienPagination.innerText);
            // mets à jour la variable globale
            nbPagination = page;

        
            fetch(url.pathname + "?" + params.toString() + "&page=" + nbPagination + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(r => r.text())
            .then(html => {
                document.querySelector("#flash-container").innerHTML = html;

                // relance les events sur la nouvelle pagination
                attachPaginationEvents(maxPagePagination, params);
                clickFlash();
                changeCurrentSpanToP(maxPagePagination);
                return;
            })
            .catch(e => console.error(e));
        };
    }
}

if(current && pagination)
    attachPaginationEvents(maxPagePagination, params)    

console.log(popupArea);
if(croixDialogsArea)
{
    croixDialogsArea.addEventListener("click", () => {
        popupArea.classList.remove("hidden");
        popupArea.showModal();
        body.classList.add("disableScroll")
    })
}