<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\Compra;
use App\Models\Fatura;
use DateTime;
use Illuminate\Http\Request;
use App\Http\service\FaturaService;


class CompraController extends Controller {

   

    public function __construct()
    {
    
    }

    public function index() {
        return Compra::all('*');
    }

    public function criar(Request $request) {
        /*----criando compra e buscando o cartão---*/
        $compra = new Compra($request);
        $cartao = Cartao::find($request->id_cartao);

        /*----alterando valor caso a parcela seja maior que 1---*/
        $compra->valor = $compra->parcelas > 1 ? $compra->valor / $compra->parcelas : $compra->valor;

        /*----tratando a data para cadastro da fatura---*/
        $data = new Datetime($compra->data_compra);
        $dadosTime = getdate($data->getTimestamp());
        $ano = $dadosTime['year'];
        $mes = $dadosTime['mon'];
        $day = $dadosTime['mday'];
        if ($day > $cartao->dia_vencimento) {
            $id_cartao_data = "$cartao->id$mes$ano";
        }

        /*----buscando a fatura---*/
        $fatura = Fatura::where('id_cartao', $compra->id_cartao)
            ->where('data', '>', $data)
            ->where('isFechada', 0)->first();

        /*----criando a fatura---*/
        if (is_null($fatura)) {
            if ($day > $cartao->dia_vencimento) {
                $nova_data = date_add($data, date_interval_create_from_date_string('1 Month'));
                $dadosTime = getdate($nova_data->getTimestamp());
                $ano = $dadosTime['year'];
                $mes = $dadosTime['mon'];
                $day = $dadosTime['mday'];
            }
            $id_cartao_data = "$cartao->id$mes$ano";
            $novaFatura = FaturaService::criarObjectFatura(
                $compra->valor,
                new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                0,
                0,
                $cartao->id,
                $id_cartao_data
            );
            $novaFatura->save();
        } else {
            $fatura->valor += $compra->valor;
            $fatura->update();
        }

        if ($compra->parcelas > 1) {
            for ($i = 1; $i < $compra->parcelas; $i++) {
                $nova_data = date_add($data, date_interval_create_from_date_string($i . ' Month'));
                $fatura = Fatura::where('id_cartao', $compra->id_cartao)
                    ->where('data', '>=', $nova_data)->first();
                $dadosTime = getdate($nova_data->getTimestamp());
                $ano = $dadosTime['year'];
                $mes = $dadosTime['mon'];
                $day = $dadosTime['mday'];
                if (is_null($fatura)) {
                    $id_cartao_data = "$cartao->id$mes$ano";
                    $novaFatura = FaturaService::criarObjectFatura(
                        $compra->valor,
                        new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                        0,
                        0,
                        $cartao->id,
                        $id_cartao_data
                    );
                    $novaFatura->save();
                } else {
                    $fatura->valor += $compra->valor;
                    $fatura->update();
                }
            }
        }
    }
}
