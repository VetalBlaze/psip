// 1. Функция активации промокода (вызывается через псевдопротокол javascript:)
// Демонстрирует использование: if, switch, prompt, alert, return, break
function activatePromoCode() {
    // Всплывающее окно ввода данных prompt()
    let code = prompt("Введите промокод для активации скидки (например: CYBER, CS, DOTA):");
    
    // Оператор if и инструкция return
    if (!code) {
        alert("Ввод отменен или поле осталось пустым.");
        return; 
    }
    
    let processedCode = code.toUpperCase().trim();
    let discountMessage = "";
    
    // Оператор switch и инструкция break
    switch (processedCode) {
        case "CYBER":
            discountMessage = "Вы получили 15% скидку на игровое время в любой зоне!";
            break;
        case "CS":
            discountMessage = "Вы получили 1 час бесплатной игры при бронировании от 3 часов!";
            break;
        case "DOTA":
            discountMessage = "Скидка 20% на ночной пакет Standart+!";
            break;
        default:
            alert("К сожалению, данный промокод не существует или его срок действия истек.");
            return;
    }
    
    // Всплывающее окно сообщения alert()
    alert("Успешно активировано!\n" + discountMessage);
}

// 2. Обработка отправки формы бронирования
// Демонстрирует использование: do..while, for, while, continue, confirm, alert
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".booking-form");
    
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Предотвращаем стандартную отправку формы
            
            const name = document.querySelector(".name-input").value;
            const date = document.querySelector(".date-input").value;
            const time = document.querySelector(".time-input").value;
            const guestsCount = parseInt(document.querySelector(".guest-select").value) || 1;
            
            // Диалоговое окно подтверждения confirm()
            let isConfirmed = confirm(`Вы действительно хотите забронировать места?\nИмя: ${name}\nДата: ${date}\nВремя: ${time}\nКоличество гостей: ${guestsCount}`);
            
            if (!isConfirmed) {
                alert("Бронирование отменено.");
                return;
            }
            
            // Демонстрация do..while (имитация проверки подключения к базе данных бронирования)
            let attempts = 0;
            let connectionSuccess = false;
            do {
                attempts++;
                if (Math.random() > 0.2) {
                    connectionSuccess = true;
                }
            } while (!connectionSuccess && attempts < 3);
            
            if (!connectionSuccess) {
                alert("Ошибка сервера. Пожалуйста, попробуйте забронировать позже.");
                return;
            }
            
            // Демонстрация симуляции распределения мест (for, continue, break)
            // Имеется 10 условных компьютеров в клубе
            let computers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            let occupiedComputers = [3, 4, 8]; // Занятые на это время ПК
            let assignedComputers = [];
            
            for (let i = 0; i < computers.length; i++) {
                let pcNumber = computers[i];
                
                // Оператор continue (пропускаем уже занятые компьютеры)
                if (occupiedComputers.includes(pcNumber)) {
                    continue; 
                }
                
                assignedComputers.push(pcNumber);
                
                // Оператор break (прекращаем поиск, когда мест набралось достаточно для гостей)
                if (assignedComputers.length === guestsCount) {
                    break;
                }
            }
            
            // Демонстрация цикла while (формируем строковое перечисление назначенных ПК)
            let index = 0;
            let assignedListString = "";
            while (index < assignedComputers.length) {
                assignedListString += "ПК №" + assignedComputers[index];
                if (index < assignedComputers.length - 1) {
                    assignedListString += ", ";
                }
                index++;
            }
            
            // Итоговый вывод результатов
            if (assignedComputers.length < guestsCount) {
                alert(`Извините, на выбранное время нет достаточного количества свободных мест.\nДоступно только компьютеров: ${assignedComputers.length}`);
            } else {
                alert(`Бронирование успешно оформлено!\nДля вас выделены: ${assignedListString}.\nЖдем вас на Профсоюзной!`);
                form.reset();
            }
        });
    }
});