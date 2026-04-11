function editRoom(id, roomNumber, roomTypeId, amenities, images, priceHourly, priceOvernight, priceDay) {
    document.getElementById('editRoomId').value        = id;
    document.getElementById('editRoomNumber').value    = roomNumber;
    document.getElementById('editRoomType').value      = roomTypeId;
    document.getElementById('editAmenities').value     = Array.isArray(amenities) ? amenities.join(', ') : amenities;
    document.getElementById('editPriceHourly').value   = priceHourly;
    document.getElementById('editPriceOvernight').value= priceOvernight;
    document.getElementById('editPriceDay').value      = priceDay;
    document.getElementById('editExistingImages').value= JSON.stringify(images);

    const previewContainer = document.getElementById('editCurrentImages');
    previewContainer.innerHTML = '';

    if(images && images.length > 0){
        images.forEach(function(filename){
            const img       = document.createElement('img');
            img.src         = '/storage/rooms/' + filename;
            img.alt         = filename;
            img.className   = 'rounded';
            img.style.cssText = 'width:72px;height:72px;object-fit:cover;';
            previewContainer.appendChild(img);
        });
    } else {
        previewContainer.innerHTML = '<small class="text-muted">No images uploaded.</small>';
    }
}

function viewRoom(id, roomNumber, roomTitle, amenities, images, priceHourly, priceOvernight, priceDay) {
    document.getElementById('viewRoomNumber').textContent    = roomNumber;
    document.getElementById('viewRoomTitle').textContent      = roomTitle;
    document.getElementById('viewAmenities').textContent     = Array.isArray(amenities) ? amenities.join(', ') : amenities;
    document.getElementById('viewPriceHourly').textContent   = '₱' + parseFloat(priceHourly).toFixed(2);
    document.getElementById('viewPriceOvernight').textContent= '₱' + parseFloat(priceOvernight).toFixed(2);
    document.getElementById('viewPriceDay').textContent      = '₱' + parseFloat(priceDay).toFixed(2);

    const imgContainer = document.getElementById('viewImages');
    imgContainer.innerHTML = '';
    if(images && images.length > 0){
        images.forEach(function(filename){
            const img       = document.createElement('img');
            img.src         = '/storage/rooms/' + filename;
            img.alt         = filename;
            img.className   = 'rounded';
            img.style.cssText = 'width:90px;height:90px;object-fit:cover;';
            imgContainer.appendChild(img);
        });
    } else {
        imgContainer.innerHTML = '<small class="text-muted">No images.</small>';
    }
}