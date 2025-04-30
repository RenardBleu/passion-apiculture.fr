const deleteModal = document.getElementById('delete-modal')
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