let navbar = document.querySelector("header .icons");
let accountBox = document.querySelector("header .account-box");
let delBtn=document.querySelector('section .container .box a');
let popup=document.querySelector('.delete-popup');
let id=document.querySelector('.delete-popup .delete-content div form .delete-id');

document.querySelector(".menu-icon").onclick = () => {
    accountBox.classList.toggle("active");
    navbar.classList.remove("active");
};
document.querySelector("header .icons .user-icon").onclick = () => {
    accountBox.classList.toggle("active");
    navbar.classList.remove("active");
};

window.onscroll = () => {
    navbar.classList.remove("active");
    accountBox.classList.remove("active");
    popup.classList.remove('active');
};

delBtn.addEventListener('click',()=>{
    
    let idval=delBtn.dataset.id;
    
    id.value=idval;
    popup.classList.toggle('active');
    
})
$('section .container .box a.delete').click(function(){
    $('.delete-id').val($(this).data('id'));
    $('.delete-popup').fadeIn(200);
});

$('.cancel-btn').click(function(){
    
    $('.delete-popup').fadeOut(200);
});