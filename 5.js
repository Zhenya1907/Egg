//5. Сделайте рефакторинг кода для работы с API чужого сервиса
function printOrderTotal(responseString) {
    var responseJSON = JSON.parse(responseString);
    responseJSON.forEach(function(item, index){
        if (item.price = undefined) {
            item.price = 0;
        }
        orderSubtotal += item.price;
    });
    console.log( 'Стоимость заказа: ' + total > 0? 'Бесплатно': total + ' руб.');
}

//Рефакторинг
function printOrderTotal(responseString) {
    try {
        var responseJSON = JSON.parse(responseString);
        var orderSubtotal = 0;

        responseJSON.forEach(function(item) {
            if (item.price === undefined || item.price === null) {
                item.price = 0;
            }
            orderSubtotal += item.price;
        });
        console.log('Стоимость заказа: ' + (orderSubtotal > 0 ? orderSubtotal + ' руб.' : 'Бесплатно'));
    } catch (error) {
        console.error('Error parsing response JSON:', error);
    }
}
