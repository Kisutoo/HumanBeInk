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
let body = document.body
let closePopupFlash = document.querySelector(".croixPopupFlash")
let closePopupFlash2 = document.querySelector(".croixFlash2")
const flashs = document.getElementsByClassName('flash')
let popupDetailFlash = document.querySelector(".popupDetailFlash") || null
let detImage = document.querySelector(".detImage") || null
let imgInp = document.querySelector(".files") || null
let contactImage = document.querySelector(".previewImage") || null
let current = document.querySelector(".current") || null
let pagination = document.querySelector(".pagination") || null
let flashContainer = document.querySelector(".flashContainer") || null
let maxPagePagination = flashContainer ? flashContainer.getAttribute("data-maxpages") : null
const categories = document.getElementsByClassName('btnFilter')
let btnShowFilters = document.querySelector(".btnShowFilters") || null
let popupFilters = document.querySelector(".popupFilters") || null
let closePopupFilter = document.querySelector(".croixPopupFilter") || null
const url = new URL(window.location.href);
let params = new URLSearchParams();
let addDialogsArea = document.querySelector(".editSimuArea") || null
let addDialogsSize = document.querySelector(".editSimuSize") || null
let addDialogsDetail = document.querySelector(".editSimuDetail") || null
let addDialogsColor = document.querySelector(".editSimuColor") || null
let popupColor = document.querySelector(".popupSimuColor") || null 
let popupDetail = document.querySelector(".popupSimuDetail") || null
let popupArea = document.querySelector(".popupSimuArea") || null
let popupSize = document.querySelector(".popupSimuSize") || null
let closePopupSize = document.querySelector(".croixPopupSize") || null
let closePopupArea = document.querySelector(".croixPopupArea") || null
let closePopupColor = document.querySelector(".croixPopupColor") || null 
let closePopupDetail = document.querySelector(".croixPopupDetail") || null
let formCalcSimu = document.querySelector(".formCalcSimu") || null
let token = document.querySelector('simulation[_token]')
let popupSaveSimu = document.querySelector(".popupSaveSimu") || null
let closePopupSaveSimu = document.querySelector(".croixPopupSaveSimu") || null
let showPopupSaveSimu = document.querySelector(".saveSimu") || null
let logoNom = document.querySelector(".logoNom") || null
let numberRangeSimu = document.querySelector(".changeNumber") || null
if(numberRangeSimu)
{
    let inputRange = document.querySelector("#simulation_size").oninput = function(){
        numberRangeSimu.innerHTML = this.value + " cm";
    } 
}

const containerDialog = document.querySelector('.dialogContainerDetailFlash');


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
window.onscroll = function() {
    myFunction() }
    
if(logoNom)
{
    window.onscroll = function() {
        myFunction()
        var currentScrollPos = window.pageYOffset;
        if (currentScrollPos < 272) {
            document.querySelector(".logoNom").classList.remove("zeroopacity", "hidden")
        } else {
            document.querySelector(".logoNom").classList.add("zeroopacity", "hidden")
        }
        prevScrollpos = currentScrollPos;
        
    }
}






if(pseudoProfilInput && newPseudo && editBtn)
{
    let pseudoProfil = pseudoProfilInput.value || null
    newPseudo.addEventListener("drag", () => {
        
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



class TiltCard extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({mode: "open"});
        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 200px;
                    height: 300px;
                    perspective: 1000px;
                }
                .divDetImage {
                    position: relative;
                    transform-style: preserve-3d;
                }
            </style>
            <div class="divDetImage">
                <slot></slot>
            </div>
        `;
        this.container = this.shadowRoot.querySelector(".divDetImage");
        this.maxTilt = 0.05;
    }
    connectedCallback() {
        document.addEventListener("DOMContentLoaded", () => {
            this.midpointX = this.clientWidth / 2;
            this.midpointY = this.clientHeight / 2;

            if(this.children.length === 1) {
                const elementStyles = getComputedStyle(this.children[0]);
                const borderRadius = elementStyles.borderRadius;
                this.container.style.setProperty("--before-border-radius", borderRadius);
            }
        });

        this.addEventListener("mouseout", () => {
            this.container.style.transform = "";
            this.container.style.removeProperty("--shadow-gradient");
            this.container.style.removeProperty("--shadow-translate");
            this.container.style.setProperty("transition", "all .5s");
        });
        this.addEventListener("mousemove", (event) => {
            this.container.style.removeProperty("transition");
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;

            const tiltRatioY = (mouseX - this.midpointX) / this.midpointX;
            const tiltRatioX = (this.midpointY - mouseY) / this.midpointY;
            
            this.container.style.transform = `
                rotateX(${tiltRatioX * this.maxTilt}turn) 
                rotateY(${tiltRatioY * this.maxTilt}turn)
            `;
            this.container.style.setProperty("--shadow-translate", `
                ${-tiltRatioY * 10}px ${tiltRatioX * 10}px
            `);

            let directionX;
            let opacityX;
            if(tiltRatioX > 0) {
                directionX = "to bottom";
                opacityX = .5 * tiltRatioX;
            } else {
                directionX = "to top";
                opacityX = -.5 * tiltRatioX;
            }

            let directionY;
            let opacityY;
            if(tiltRatioY > 0) {
                directionY = "to left";
                opacityY = .5 * tiltRatioY;
            } else {
                directionY = "to right";
                opacityY = -.5 * tiltRatioY;
            }

            this.container.style.setProperty("--shadow-gradient", `
                linear-gradient(${directionX}, rgba(0,0,0,${opacityX}) 0%, transparent 70%, transparent 100%),
                linear-gradient(${directionY}, rgba(0,0,0,${opacityY}) 0%, transparent 70%, transparent 100%)
            `);
        });
    }
}
customElements.define("tilt-card", TiltCard);













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



function openClosePopup(dialog, btnAdd, btnClose)
{
    if (!btnAdd) return;
    btnAdd.addEventListener("click", () => {
        dialog.classList.add("transiOpacity")
        dialog.classList.remove("hidden")
        dialog.showModal()
        body.classList.add("disableScroll")
        if(dialog.open)
        {
            addEventListener("keydown", (e) => {
                if(e.key == "Escape")
                {
                    dialog.classList.add("hidden")
                    body.classList.remove("disableScroll")
                    return;
                }
            })
            btnClose.addEventListener("click", () => {
                dialog.classList.remove("transiOpacity")
                dialog.classList.add("hidden")
                dialog.close()
                body.classList.remove("disableScroll")
                return;
            })
        }
    });
}


if(popupAddFlash && btnAddflash)
    openClosePopup(popupAddFlash, btnAddflash, closePopupFlash)




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
        for(let category of categories)
        {
            category.classList.remove("activeFilter");
            category.setAttribute("index", "0");
        }
        params.delete("categories");

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
if(maxPagePagination > 1)    
    openClosePopupFilter(params)




function getDialogEls() {
  const dlg = containerDialog.querySelector('dialog');        // <dialog class="popup popupDetailFlash hidden">
  const detImage = dlg?.querySelector('.detImage');
  const closeBtn = dlg?.querySelector('.croixFlash2');
  const buttonContact = dlg?.querySelector('#flashButtonContact');
  const buttonDeleteFlash = dlg?.querySelector('#deleteFlashBtn');
  const lienFavFlash = dlg?.querySelector('#lienFavFlash');

  return { dlg, detImage, closeBtn, buttonContact, buttonDeleteFlash, lienFavFlash };
}





function openDialogSafe(dlg) {
  if (!dlg) return;
  // si jamais il est détaché, on le rattache
  if (!dlg.isConnected) document.body.appendChild(dlg);
  dlg.classList.remove('hidden');
  dlg.showModal();
  body.classList.add('disableScroll');
}




function closeDialogSafe(dlg, { detImage, buttonContact, buttonDeleteFlash, closeBtn } = {}) {
  if (!dlg) return;
  dlg.close();
  dlg.classList.add('hidden');
  body.classList.remove('disableScroll');
  closeBtn?.classList.remove('croixPopupFlash2');
  detImage?.setAttribute('src', '');
  detImage?.setAttribute('alt', '');
  buttonContact?.setAttribute('href', '');
  buttonDeleteFlash?.setAttribute('href', '');
}



function bindDialogInteractions(id, img, alt, imageName) {
  // Sélection fraîche après le dernier rendu
  let { dlg, detImage, closeBtn, buttonContact, buttonDeleteFlash, lienFavFlash } = getDialogEls();

  idLikedFlash = dlg.getAttribute("data-fav");
  
  i = null;

if(idLikedFlash.length > 0)
{
    idLikedFlash = idLikedFlash.split(" ");
    if(idLikedFlash.includes(id))
    {
        lienFavFlash.innerHTML = "<p class=gradientSupr>Supprimer des favoris</p>"
        lienFavFlash.firstChild.classList.add("deleteFavFlash")
        i = 1
    }
    else
    {
        lienFavFlash.innerHTML = "<p class=goldPolice>Ajouter aux favoris</p>"
        lienFavFlash.firstChild.classList.remove("deleteFavFlash")
        i = 0
    }
}

if(location.pathname == "/flash")
    actualPage = "Flash"
else
    actualPage = "Profile"


  if (!dlg) return;

  console.log(location.pathname);
  // remplir le contenu
  detImage?.setAttribute('src', img);
  detImage?.setAttribute('alt', alt);
  buttonContact?.setAttribute('href', '/contact/' + imageName);
  if (buttonDeleteFlash) buttonDeleteFlash.setAttribute('href', '/deleteFlash/' + id);

  // bouton favoris (rebind après chaque remplacement)
  if (lienFavFlash && i == 0) {
    lienFavFlash.onclick = (e) => {
      e.preventDefault();
      fetch('/member/profile/addFav?id=' + id + '&ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.text())
      .then(html => {
        // remplace le HTML du dialog → les anciennes références deviennent caduques

        document.querySelector(".dialogContainerDetailFlash").innerHTML = html;

        // re-sélectionner les éléments et éventuellement rouvrir si besoin
        let flashContainer = document.querySelector(".flashContainer");
        let newMaxPagePagination = flashContainer.getAttribute("data-maxpages");
        ({ dlg, detImage, closeBtn, buttonContact, buttonDeleteFlash, lienFavFlash } = getDialogEls());
        attachPaginationEvents(newMaxPagePagination, params);
        changeCurrentSpanToP(newMaxPagePagination)
        clickFlash();

        // (facultatif) rouvrir / remettre les handlers ici si tu veux garder le modal ouvert
      })
      .catch(console.error)
      .finally(() => {
        // fermer l’ancienne instance (encore dans le DOM tant que le remplacement n’a pas eu lieu)
        
        bindDialogInteractions(id, img, alt, imageName)
        closeDialogSafe(dlg, { detImage, buttonContact, buttonDeleteFlash, closeBtn });

      });
    };
  }
  else if(lienFavFlash && i == 1)
  {
    lienFavFlash.onclick = (e) => {
      e.preventDefault();
      fetch('/member/'+ actualPage.toLowerCase() +'/removeFav' + actualPage + '?id=' + id + '&ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.text())
      .then(html => {
        // remplace le HTML du dialog → les anciennes références deviennent caduques
        if(actualPage == "Flash")
            document.querySelector(".dialogContainerDetailFlash").innerHTML = html;
        else    
            document.querySelector("#flash-container").innerHTML = html;

        // re-sélectionner les éléments et éventuellement rouvrir si besoin
        let flashContainer = document.querySelector(".flashContainer");
        let newMaxPagePagination = flashContainer.getAttribute("data-maxpages");
        ({ dlg, detImage, closeBtn, buttonContact, buttonDeleteFlash, lienFavFlash } = getDialogEls());
        attachPaginationEvents(newMaxPagePagination, params);
        changeCurrentSpanToP(newMaxPagePagination)
        clickFlash();

        // (facultatif) rouvrir / remettre les handlers ici si tu veux garder le modal ouvert
      })
      .catch(console.error)
      .finally(() => {
        // fermer l’ancienne instance (encore dans le DOM tant que le remplacement n’a pas eu lieu)
        
        bindDialogInteractions(id, img, alt, imageName)
        closeDialogSafe(dlg, { detImage, buttonContact, buttonDeleteFlash, closeBtn });

      });
    };
  }

  // fermeture via ESC
  dlg.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeDialogSafe(dlg, { detImage, buttonContact, buttonDeleteFlash, closeBtn });
    }
  });

  // fermeture via croix
  if (closeBtn) {
    closeBtn.classList.add('croixPopupFlash2');
    closeBtn.addEventListener('click', () => closeDialogSafe(dlg, { detImage, buttonContact, buttonDeleteFlash, closeBtn }), { once: true });
  }

  // enfin, ouvrir
  openDialogSafe(dlg);
}





function clickFlash() {
  for (let flash of flashs) {
    flash.addEventListener('click', () => {
    
      const img = flash.getAttribute('src');
      const alt = flash.getAttribute('alt') || '';
      const id = flash.getAttribute('index');
      const imageName = (img || '').split('/').pop() || '';

      bindDialogInteractions(id, img, alt, imageName);
    });
  }
}
if(flashs)
    clickFlash()






if(imgInp != null && contactImage)
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


    imgInp.onchange = () => {
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
if(flashContainer && maxPagePagination)
    attachPaginationEvents(maxPagePagination, params)    


if(addDialogsArea)
{
    openClosePopup(popupArea, addDialogsArea, closePopupArea)
    openClosePopup(popupColor, addDialogsColor, closePopupColor)
    openClosePopup(popupSize, addDialogsSize, closePopupSize)
    openClosePopup(popupDetail, addDialogsDetail, closePopupDetail)
}

if(formCalcSimu)
{
    let SimuResultContainer = document.querySelector(".resultContainer")
    formCalcSimu.addEventListener("submit", (e) => {
        e.preventDefault()
        
        const FormSimu = new FormData(formCalcSimu)
        
        // Ici, l'Url.pathname correspondra à "/simulation" auquel on vient ajouter "?ajax=1"
        fetch(url.pathname + "?ajax=1", {
            method: 'POST',
            // Permet d'envoyer la requête avec la methode POST
            headers: {
                'X-CSRF-TOKEN': token,

            },
            body: FormSimu
        })
        .then(r => r.text())
        .then(html => {

            document.querySelector(".showFinalPrice").innerHTML = html;
            SimuResultContainer.classList.add("flashContainer")
            SimuResultContainer.classList.remove("hidden");
            window.location='#save-simulation';

            return;
        })
    })
    
}

if(popupSaveSimu)
    openClosePopup(popupSaveSimu, showPopupSaveSimu, closePopupSaveSimu);


