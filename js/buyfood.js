let orderList = [];

function openModal() {
    document.getElementById('orderModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

function addToOrder(foodId, foodName,  price) {
    const quantity = parseInt(document.getElementById(`quantity-${foodId}`).value);
    if (quantity > 0) {
        orderList.push({ foodId, foodName, quantity, price });
        renderOrderList();
        alert(`${foodName} added to order!`);
    } else {
        alert('Please select a valid quantity.');
    }
}

function renderOrderList() {
    const orderContainer = document.getElementById('orderList');
    orderContainer.innerHTML = '';
    orderList.forEach(item => {
        const div = document.createElement('div');
        div.className = 'order-item';
        div.innerHTML = `
            <span>${item.foodName}</span>
            <span>Quantity: ${item.quantity}</span>
        `;
        orderContainer.appendChild(div);
    });
}

function submitOrder() {
    if (orderList.length === 0) {
        alert('Your order is empty.');
        return;
    }

    // Collect necessary data
    const customerId = document.getElementById('customerId').value;
    const orderData = {
        customer_id: customerId,
        items: orderList
    };

    // Send the data to the server using fetch
    fetch('../dbserver/orderC.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order submitted successfully!');
                orderList = [];
                renderOrderList();
                closeModal();
            } else {
                alert('Failed to submit order: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the order.');
        });
}

