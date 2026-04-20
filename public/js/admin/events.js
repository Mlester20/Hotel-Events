function editEvent(id, title, description, location, capacity, price, image){
    document.getElementById('editEventId').value = id
    document.getElementById('Edittitle').value = title
    document.getElementById('Editdescription').value = description
    document.getElementById('Editlocation').value = location
    document.getElementById('Editcapacity').value = capacity
    document.getElementById('Editprice').value = price
    
    // Display current image if exists
    if (image) {
        document.getElementById('editCurrentImage').innerHTML = '<img src="../../../storage/events/' + image + '" alt="Current Event Image" style="max-width: 150px; max-height: 100px;">'
    } else {
        document.getElementById('editCurrentImage').innerHTML = '<p class="text-muted">No image uploaded</p>'
    }
}

function viewEvent(id, title, description, location, capacity, price, image){
    document.getElementById('viewTitle').textContent = title
    document.getElementById('viewDescription').textContent = description
    document.getElementById('viewLocation').textContent = location
    document.getElementById('viewCapacity').textContent = capacity
    document.getElementById('viewPrice').textContent = '₱' + parseFloat(price).toFixed(2)
    
    // Display image
    const imgElement = document.getElementById('viewImage')
    if (image) {
        imgElement.src = '../../../storage/events/' + image
        imgElement.style.display = 'block'
    } else {
        imgElement.style.display = 'none'
    }
}