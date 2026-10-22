<p>Новая заявка на прайс лист</p>
<ul>
    <li>Организация: {{ $lead->name }}</li>
    <li>Телефон: {{ $lead->phone }}</li>
    <li>Email: {{ $lead->email !== '' ? $lead->email : '-' }}</li>
    <li>ИНН: {{ $lead->inn ?: '-' }}</li>
    <li>Формат работы: {{ $lead->contact_preference ?: '-' }}</li>
    <li>Комментарий: {{ $lead->comment ?: '-' }}</li>
    <li>Статус: {{ $lead->status }}</li>
    <li>Создано: {{ $lead->created_at }}</li>
</ul>
