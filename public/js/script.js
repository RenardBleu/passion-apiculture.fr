const deleteModal = document.getElementById('delete-modal')
const deleteModalType = document.getElementById('deleteModalType')
const editModalType = document.getElementById('editModalType')

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