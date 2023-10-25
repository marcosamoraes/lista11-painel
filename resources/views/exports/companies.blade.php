<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Vendedor</th>
        <th>Cliente</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Whatsapp</th>
        <th>Cidade</th>
        <th>Estado</th>
        <th>Status</th>
        <th>Vence em</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
    </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{{ $company->id }}</td>
            <td>{{ $company->user?->name ?? 'Sem vendedor' }}</td>
            <td>{{ $company->client->user->name }}</td>
            <td>{{ $company->name }}</td>
            <td>{{ $company->client->user->email }}</td>
            <td>{{ $company->phone }}</td>
            <td>{{ $company->phone2 }}</td>
            <td>{{ $company->city }}</td>
            <td>{{ $company->state }}</td>
            <td>
                @if ($company->is_approved)
                    Ativo
                @else
                    Inativo
                @endif
            </td>
            <td>{{ $company->lastOrderApproved?->expire_at?->format('d/m/Y') }}</td>
            <td>{{ $company->created_at }}</td>
            <td>{{ $company->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
