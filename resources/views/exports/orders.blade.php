<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Vendedor</th>
        <th>Empresa</th>
        <th>E-mail</th>
        <th>Cidade</th>
        <th>Estado</th>
        <th>Status</th>
        <th>Valor</th>
        <th>Aprovado em</th>
        <th>Vence em</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user?->name ?? 'Sem vendedor' }}</td>
            <td>{{ $order->company->name }}</td>
            <td>{{ $order->company->client->user->email }}</td>
            <td>{{ $order->company->city }}</td>
            <td>{{ $order->company->state }}</td>
            <td>
                @if ($order->status === 'pending')
                    Pendente
                @elseif ($order->status === 'approved')
                    Aprovado
                @elseif ($order->status === 'canceled')
                    Cancelado
                @else
                    Reembolsado
                @endif
            </td>
            <td>R$ {{ number_format($order->value, 2, ',', '.') }}</td>
            <td>{{ $order->approved_at?->format('d/m/Y H:i:s') }}</td>
            <td>{{ $order->expire_at?->format('d/m/Y') }}</td>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
