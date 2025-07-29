let burgerIcon = document.querySelector(".burger")
let popupBurger = document.querySelector(".menu-burger")
let closeBurger = document.querySelector(".croixCloseBurger")

burgerIcon.addEventListener("click", () => {
    popupBurger.classList.toggle("letVisible")
})

closeBurger.addEventListener("click", () => {
    popupBurger.classList.toggle("letVisible")
})