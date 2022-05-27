let navLinks=document.querySelectorAll('header nav a');
let userIcon=document.querySelector('header .icons .user-icon');
let accountBox=document.querySelector('header .account-box');
let menuIcon=document.querySelector('header .icons .menu-icon');
let accountBoxMenu=document.querySelector('header .account-box-menu');


// accountBox.innerHTML+='<h1 style="color:black;">hello</h1>';


userIcon.addEventListener('click',function(){
        accountBox.classList.toggle('active');
});

window.onscroll=()=>{
    accountBox.classList.remove('active');
    accountBoxMenu.classList.remove('active');
};

if(window.sessionStorage.getItem('activeLink')){
    navLinks.forEach((ele)=>{

        ele.classList.remove("active");
        if(sessionStorage.getItem('activeLink')===ele.dataset.name)
        ele.classList.add("active");
    })
}

navLinks.forEach((ele)=>{

    ele.addEventListener("click", (e) => {
        navLinks.forEach((element) => {
            element.classList.remove("active");
        });
        e.currentTarget.classList.add("active");
        sessionStorage.setItem("activeLink", e.currentTarget.dataset.name);
        
    });
})

menuIcon.addEventListener('click',()=>{
    accountBoxMenu.classList.toggle('active');
})



