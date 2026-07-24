<p>Новая заявка на прайс лист</p>
<ul>
    <li>ФИО: {{ $lead->name }}</li>
    <li>Телефон: {{ $lead->phone }}</li>
    <li>Email: {{ $lead->email }}</li>
    <li>ИНН: {{ $lead->inn ?: '-' }}</li>
    <li>Город: {{ $lead->city ?: '-' }}</li>
    <li>Как связаться: {{ $lead->contact_preference ?: '-' }}</li>
    <li>Комментарий: {{ $lead->comment ?: '-' }}</li>
    <li>Статус: {{ $lead->status }}</li>
    <li>Создано: {{ $lead->created_at }}</li>
</ul>
