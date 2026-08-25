async function loadProducts(){
  const box=document.querySelector('#products');
  const q=(document.querySelector('#search')?.value||'').toLowerCase();
  const r=await fetch('api/products.php');
  const products=await r.json();
  box.innerHTML='';
  products.filter(p=>(p.name+' '+p.category+' '+p.description).toLowerCase().includes(q)).forEach(p=>{
    const img=p.image||'assets/placeholder.svg';
    box.insertAdjacentHTML('beforeend',`
      <article class="product">
        <img src="${escapeHtml(img)}" onerror="this.src='assets/placeholder.svg'">
        <div class="p">
          <div class="muted">${escapeHtml(p.category)}</div>
          <h3>${escapeHtml(p.name)}</h3>
          <p>${escapeHtml(p.description)}</p>
          <div class="price">${Number(p.price).toLocaleString('fr-FR')} FCFA</div>
          <button class="btn" onclick="addToCart(${p.id})">Ajouter au panier</button>
        </div>
      </article>`);
  });
}
function escapeHtml(v){return String(v).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function addToCart(id){
  const cart=JSON.parse(localStorage.getItem('aubedeCart')||'[]');
  const x=cart.find(i=>i.id===id); if(x)x.qty++; else cart.push({id,qty:1});
  localStorage.setItem('aubedeCart',JSON.stringify(cart));
  alert('Produit ajouté au panier.');
}
document.addEventListener('DOMContentLoaded',()=>{loadProducts();document.querySelector('#search')?.addEventListener('input',loadProducts)});
