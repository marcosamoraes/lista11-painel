<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Vendedor</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Whatsapp</th>
        <th>Cidade</th>
        <th>Estado</th>
        <th>Status</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->seller?->name ?? 'Sem vendedor' }}</td>
            <td>{{ $client->user->name }}</td>
            <td>{{ $client->user->email }}</td>
            <td>{{ $client->phone }}</td>
            <td>{{ $client->phone2 }}</td>
            <td>{{ $client->city }}</td>
            <td>{{ $client->state }}</td>
            <td>
                @if ($client->user->status)
                    Ativo
                @else
                    Inativo
                @endif
            </td>
            <td>{{ $client->created_at }}</td>
            <td>{{ $client->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
