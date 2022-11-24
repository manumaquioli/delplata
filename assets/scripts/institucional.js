const ops = document.getElementsByClassName('titles');
ops[0].style.backgroundColor="rgb(206, 237, 255)";
const sections = document.getElementsByClassName('sections');

for(let item of ops){
    item.addEventListener('click', ()=>{
        item.style.backgroundColor='rgb(206, 237, 255)';
        for(let a of ops){
            if(a.id != event.target.id){
                a.style.backgroundColor='unset';
            }
        }
        let count = item.id.substring(2, 3);
        sections[count].classList.add('section-active');
        for(let b = 0; b < sections.length; b++){
            if(b != count){
                sections[b].classList.remove('section-active');
            }
        }
    });
}