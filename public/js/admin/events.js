function editEvent(id, title, description, location, capacity, price){
    document.getElementById('editEventId').value = id
    document.getElementById('Edittitle').value = title
    document.getElementById('Editdescription').value = description
    document.getElementById('Editlocation').value = location
    document.getElementById('Editcapacity').value = capacity
    document.getElementById('Editprice').value = price
}

function viewEvent(id, title, description, location, capacity, price){
    document.getElementById('viewTitle').textContent = title
    document.getElementById('viewDescription').textContent = description
    document.getElementById('viewLocation').textContent = location
    document.getElementById('viewCapacity').textContent = capacity
    document.getElementById('viewPrice').textContent = '₱' + parseFloat(price).toFixed(2)
}