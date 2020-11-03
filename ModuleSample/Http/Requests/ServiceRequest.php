<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Requests;

class ServiceRequest extends \App\Services\BaseRequest
{

    private function rulesPost(): array
    {
        return [
            'nome_principal'                  => 'required|max:100',
            'tipo_pessoa_id'                  => 'required|integer',
            'nome_secundario'                 => 'nullable|max:100',
            'ramo_atividade_id'               => 'nullable|integer',
            'segmento_id'                     => 'nullable|integer',
            'como_conheceu_id'                => 'nullable|integer',
            'canal_id'                        => 'nullable|integer',
            'socio_relacionamento_id'         => 'nullable|integer',
            'colaborador_id'                  => 'nullable|integer',
            'lideranca_id'                    => 'nullable|integer',
            'observacao'                      => 'nullable|max:65535',
            # enderecos
            'enderecos'                       => 'sometimes|array',
            'enderecos.*.tipo_endereco_id'    => 'required',
            'enderecos.*.cep'                 => 'required|max:15',
            'enderecos.*.pais'                => 'required|max:100',
            'enderecos.*.estado'              => 'required|max:4',
            'enderecos.*.cidade'              => 'required|max:100',
            'enderecos.*.bairro'              => 'required|max:100',
            'enderecos.*.logradouro'          => 'required|max:190',
            # contatos
            'contatos'                        => 'sometimes|array',
            'contatos.*.nome'                 => 'required|max:100',
            'contatos.*.emails'               => 'required_with:contatos.*.contato|array',
            'contatos.*.emails.*.email'       => 'required_without:contatos.*.telefones.*.telefone|email|max:100',
            'contatos.*.telefones'            => 'required_with:contatos.*.contato|array',
            'contatos.*.telefones.*.telefone' => 'required_without:contatos.*.emails.*.email|max:20',
        ];

    }

    private function rulesPut(): array
    {
        return [
            'nome_principal'                  => 'required|max:100',
            'tipo_pessoa_id'                  => 'required|integer',
            'nome_secundario'                 => 'nullable|max:100',
            'ramo_atividade_id'               => 'nullable|integer',
            'segmento_id'                     => 'nullable|integer',
            'como_conheceu_id'                => 'nullable|integer',
            'canal_id'                        => 'nullable|integer',
            'socio_relacionamento_id'         => 'nullable|integer',
            'colaborador_id'                  => 'nullable|integer',
            'lideranca_id'                    => 'nullable|integer',
            'observacao'                      => 'nullable|max:65535',
            # enderecos
            'enderecos'                       => 'sometimes|array',
            'enderecos.*.tipo_endereco_id'    => 'required',
            'enderecos.*.cep'                 => 'required|max:15',
            'enderecos.*.pais'                => 'required|max:100',
            'enderecos.*.estado'              => 'required|max:4',
            'enderecos.*.cidade'              => 'required|max:100',
            'enderecos.*.bairro'              => 'required|max:100',
            'enderecos.*.logradouro'          => 'required|max:190',
            # contatos
            'contatos'                        => 'sometimes|array',
            'contatos.*.nome'                 => 'required|max:100',
            'contatos.*.emails'               => 'required_with:contatos.*.contato|array',
            'contatos.*.emails.*.email'       => 'required_without:contatos.*.telefones.*.telefone|email|max:100',
            'contatos.*.telefones'            => 'required_with:contatos.*.contato|array',
            'contatos.*.telefones.*.telefone' => 'required_without:contatos.*.emails.*.email|max:20',
        ];

    }

}
