document.getElementById('y').textContent=new Date().getFullYear();
const t=[500,12,40];document.querySelectorAll('.stats b').forEach((el,i)=>{let n=0,s=Math.max(1,Math.ceil(t[i]/60));const id=setInterval(()=>{n=Math.min(t[i],n+s);el.textContent=n+(i==0?'+':'');if(n>=t[i])clearInterval(id)},25)});
const f=document.getElementById('contact-form'),st=document.getElementById('form-status');
f.addEventListener('submit',async e=>{e.preventDefault();st.textContent='Sending…';
try{const r=await fetch(f.action,{method:'POST',body:new FormData(f),headers:{Accept:'application/json'}});let j;try{j=await r.json()}catch(_){st.textContent='Server error ('+r.status+'). Open the site at http://localhost/ironforge/ — not by double-clicking index.html.';return}
st.textContent=j.ok?'Thanks! We will be in touch soon.':(j.error||'Something went wrong.');if(j.ok)f.reset()}
catch(_){st.textContent='Cannot reach contact.php. Start Apache in XAMPP and open http://localhost/ironforge/'}});
