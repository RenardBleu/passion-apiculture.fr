const deleteModal = document.getElementById('delete-modal')
const deleteModalType = document.getElementById('deleteModalType')
const editModalType = document.getElementById('editModalType')
const editModalProduit = document.getElementById('editModalProduit')
const deleteModalImage = document.getElementById('delete-image-modal')

if (deleteModal) {
    deleteModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const name = button.getAttribute('data-bs-name')
    const id = button.getAttribute('data-bs-id')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalProductName = deleteModal.querySelector('#product-delete-name')
    const modalProductBtn = deleteModal.querySelector('#btn-delete-product')

    modalProductName.textContent = name

    modalProductBtn.href = `index.php?page=admin-produit-delete&id=${id}`
  })
}

if (deleteModalType) {
  deleteModalType.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const buttonType = event.relatedTarget
    // Extract info from data-bs-* attributes
    const nameType = buttonType.getAttribute('data-bs-nameType')
    const idType = buttonType.getAttribute('data-bs-idType')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTypeName = deleteModalType.querySelector('#type-delete-name')
    const modalTypeBtn = deleteModalType.querySelector('#btn-delete-type')

    modalTypeName.textContent = nameType

    modalTypeBtn.href = `index.php?page=admin-type-delete&id=${idType}`
  })
}

if (editModalType) {
  editModalType.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const buttonEditType = event.relatedTarget
    // Extract info from data-bs-* attributes
    const nameEditType = buttonEditType.getAttribute('data-bs-nameEditType')
    const idEditType = buttonEditType.getAttribute('data-bs-idEditType')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTypeName = editModalType.querySelector('#type-edit-name')
    const modalTypeInput = editModalType.querySelector('#type-edit-input')
    const modalTypeForm = editModalType.querySelector('#type-edit-form')

    modalTypeName.textContent = nameEditType
    modalTypeInput.value = nameEditType

    modalTypeForm.action = `index.php?page=admin-type-edit&id=${idEditType}`
  })
}

if (editModalProduit) {
  editModalProduit.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const buttonEditProduit = event.relatedTarget

    // Extract info from data-bs-* attributes
    const produitName = buttonEditProduit.getAttribute('data-bs-name')
    const produitId = buttonEditProduit.getAttribute('data-bs-id')
    const produitPrix = buttonEditProduit.getAttribute('data-bs-prix')
    const produitDescription = buttonEditProduit.getAttribute('data-bs-description')
    const produitType = buttonEditProduit.getAttribute('data-bs-type')
    const produitImage = buttonEditProduit.getAttribute('data-bs-miniature')
    const produitStock = buttonEditProduit.getAttribute('data-bs-stock')
    const produitCaracteristique = buttonEditProduit.getAttribute('data-bs-caracteristiques')

    // Update the modal's content
    const modalProduitName = editModalProduit.querySelector('input[name="nom"]')
    const modalProduitPrix = editModalProduit.querySelector('input[name="prix"]')
    const modalProduitDescription = editModalProduit.querySelector('textarea[name="description"]')
    const modalProduitType = editModalProduit.querySelector('#textSelectType')
    const modalProduitForm = editModalProduit.querySelector('#product-edit-form')
    const modalProduitStock = editModalProduit.querySelector('#stock')
    const modalProduitCaracteristique = editModalProduit.querySelector('#caracteristiques')
    const modalProduitMiniaContent = editModalProduit.querySelector('#miniature-content')

    // Set the values in the form
    modalProduitName.value = produitName
    modalProduitPrix.value = produitPrix
    modalProduitDescription.value = produitDescription
    modalProduitType.textContent = produitType
    modalProduitForm.action = `index.php?page=admin-produit-edit&id=${produitId}`

    if( produitImage !== '') {
      modalProduitMiniaContent.innerHTML = `
      <label for="formFile" class="form-label">Miniature du produit</label>
      <a href="#" class="delete-btn border border-danger border-3 rounded-2 bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#delete-image-modal" data-bs-nameImage="${produitImage}">
        <img src="${produitImage}" alt="Miniature" class="img-fluid">
        <div class="middle">
          <i class="bi bi-trash3-fill text-danger"></i>
        </div>
      </a>
      `
    } else {
      modalProduitMiniaContent.innerHTML = `
      <label for="formFile" class="form-label">Miniature du produit</label>
      <input name="miniature" class="form-control" type="file" id="formFile">
      `
    }
    if (produitStock !== ''){
      modalProduitStock.value = produitStock
    }else{
      modalProduitStock.value = 0
    }
    console.log(produitCaracteristique)
    modalProduitCaracteristique.value = produitCaracteristique
  })
}

if (deleteModalImage) {
  deleteModalImage.addEventListener('show.bs.modal', event => {
    const buttonType = event.relatedTarget
    const nameImage = buttonType.getAttribute('data-bs-nameImage')
    const modalImageBtn = deleteModalImage.querySelector('#btn-delete-image')

    modalImageBtn.href = `index.php?page=admin-delete-image&image_path=${nameImage}`
  })
}

function updateStatus(selectElement) {
    const commandeId = selectElement.getAttribute('data-commande-id');
    const newStatus = selectElement.value;
    
    // Mettre à jour l'attribut data-value pour le style
    selectElement.setAttribute('data-value', newStatus);
    
    // Redirection vers la page PHP qui gère la mise à jour
    window.location.href = `index.php?page=admin-commande-update-status&id=${commandeId}&status=${newStatus}`;
}