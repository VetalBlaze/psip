function calculateFormula(x, y) {
    let sqrtX = Math.sqrt(Math.abs(x));
    
    // Проверка, чтобы не извлекать логарифм из отрицательного числа
    if ((y - sqrtX) <= 0) {
        return "Ошибка: выражение под логарифмом должно быть больше нуля!";
    }

    let lnPart = Math.log(y - sqrtX);
    let denominator = x + (Math.pow(x, 2) / 4);
    let bracketPart = x - (y / denominator);

    let result = lnPart * bracketPart;
    
    // Возвращаем результат, округленный до 3 знаков после запятой
    return result.toFixed(3); 
}