# Интерактивная карта объектов капитального строительства городского округа "город Улан-Удэ"
------------
Интерактивная карта объектов капитального строительства предназначена для отображения информации о ходе работ по различным отраслевым принадлежностям.  Написана на чистом js + jQuery для совместимости со старыми версиями браузеров. Использует в качестве бэкенда систему Битрикс, в рамках которой и строятся элементы.

### Установка:
Вся система строится в формате шаблона сайта для Битрикс. Поэтому, содержимое директории распаковывается в
*/local/templates/имя_шаблона*
Шаблон формы находится в: 
*appendix/iblock_map_build_element_edit.php
Шаблон карты на форме находится в: 
*appendix/iblock_yandex_map.php*
После распаковки необходимо установить "магические константы" согласно системе инфоблоков.

### Расшифровка "магических констант":

ID инфоблока в системе Битрикс IBLOCK_ID => 302
ID элемента "Тип метки" (type) в системе Битрикс PROPERTY_ID => 1137
Список типов метки
- 531 Точка
- 532 Ломаная
- 533 Полигон

ID элемента "Группа меток" (category) в системе Битрикс PROPERTY_ID => 1136
Список отраслевых принадлежностей:
•	524 - Образование
•	525 - Здравоохранение
•	526 - Социальная защита населения
•	527 - Культура
•	528 - Физическая культура и спорт
•	529 - Строительство
•	530 - Жилищно-коммунальное хозяйство
•	558 - Энергетика
•	559 - Дорожное хозяйство и логистика
•	560 - Промышленность
•	561 - Малое предпринимательство
•	562 - Туризм
•	563 - Экология
•	564 - Занятость и безработица
•	565 - Обеспечение общественной безопасности
•	566 - Межбюджетные трансферты
