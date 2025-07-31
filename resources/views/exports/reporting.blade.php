<table>
    <thead>
        <tr>
            <th>Société</th>
            <th>Année</th>
            <th>Mois</th>
            <th>Centre Emplisseur</th>
            <th>Code Client</th>
            <th>Catégorie Client</th>
            <th>Code Région</th>
            <th>Région</th>
            <th>Préfecture</th>
            <th>Commune Découpage</th>
            <th>Commune Déclarée</th>
            <th>3kg</th>
            <th>6kg</th>
            <th>9kg</th>
            <th>12kg</th>
            <th>35kg</th>
            <th>40kg</th>
            <th>3kg VR</th>
            <th>6kg VR</th>
            <th>9kg VR</th>
            <th>12kg VR</th>
            <th>35kg VR</th>
            <th>40kg VR</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
        <tr>
            <td>{{ $record->societe }}</td>
            <td>{{ $record->annee }}</td>
            <td>{{ $record->mois }}</td>
            <td>{{ $record->centreEmplisseur?->nom }}</td>
            <td>{{ $record->client?->code_client }}</td>
            <td>{{ $record->client?->categorie }}</td>
            <td>{{ $record->region?->id }}</td>
            <td>{{ $record->region?->nom }}</td>
            <td>{{ $record->prefecture?->nom }}</td>
            <td>{{ $record->communeDecoupage?->nom }}</td>
            <td>{{ $record->commune?->nom }}</td>
            <td>{{ $record->qte_charge_3kg }}</td>
            <td>{{ $record->qte_charge_6kg }}</td>
            <td>{{ $record->qte_charge_9kg }}</td>
            <td>{{ $record->qte_charge_12kg }}</td>
            <td>{{ $record->qte_charge_35kg }}</td>
            <td>{{ $record->qte_charge_40kg }}</td>
            <td>{{ $record->qte_vendu_3kg }}</td>
            <td>{{ $record->qte_vendu_6kg }}</td>
            <td>{{ $record->qte_vendu_9kg }}</td>
            <td>{{ $record->qte_vendu_12kg }}</td>
            <td>{{ $record->qte_vendu_35kg }}</td>
            <td>{{ $record->qte_vendu_40kg }}</td>
        </tr>
        @endforeach
    </tbody>
</table>