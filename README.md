# Como Funciona o Sorteio de Avaliadores e Sua Auditoria

## Explicação Simplificada
O sistema realiza sorteios de avaliadores de forma transparente e auditável. Usamos um "número de controle" único (chamado seed) gerado a partir de informações fixas da comissão (ID da configuração da fase, nome da comissão e número do sorteio). Esse número garante que o mesmo conjunto de dados sempre produzirá o mesmo resultado. Qualquer pessoa pode verificar o processo, bastando reunir as informações originais e repetir os passos do sorteio.

## Explicação Técnica para Auditoria em PHP
A implementação do sorteio auditável utiliza a função auditableDraw, que depende de um seed determinístico para inicializar o gerador de números pseudoaleatórios do PHP (mt_srand). O seed é criado a partir das seguintes variáveis:

`$evaluation_method_configuration_id` (ID da configuração da fase de avaliação);

`$committee_name` (nome da comissão);

`$draw_number` (número sequencial do sorteio na comissão).

### Passos para Auditoria:
#### Recuperar os dados de entrada usados no sorteio:

ID da configuração da fase (`$evaluation_method_configuration_id`);

Nome da comissão (`$committee_name`);

Número do sorteio (`$draw_number`);

Lista original de IDs dos avaliadores (`$items`).

#### Gerar o seed original (exatamente como implementado):

```php
$seed = crc32($evaluation_method_configuration_id . $committee_name . $draw_number);
```

#### Reproduzir o sorteio com a função auditableDraw:

```php
function auditableDraw(int $seed, array $items, int $number_of_valuers) {
     mt_srand($seed); // Inicializa o gerador com o seed
     $shuffled_items = $items;
     shuffle($shuffled_items); // Embaralha a lista deterministicamente
     return array_slice($shuffled_items, 0, $number_of_valuers); // Seleciona os N primeiros
 }
```

Comparar o resultado com o registro armazenado no sistema.

Por que é Auditável?
O seed é derivado de dados imutáveis e públicos (ID da configuração, nome da comissão e número do sorteio);

`mt_srand()` e `shuffle()` são determinísticos quando inicializados com o mesmo seed;

A função shuffle do PHP usa o gerador Mersenne Twister, que é reproduzível com o mesmo seed.

Exemplo de Código de Validação:
```php
// Dados de entrada (exemplo)
$evaluation_method_configuration_id = 456;
$committee_name = "Comissão de Teatro";
$draw_number = 2;
$items = [10, 20, 30, 40, 50]; // Lista de IDs dos avaliadores

// Gera o seed (exatamente como na implementação real)
$seed = crc32($evaluation_method_configuration_id . $committee_name . $draw_number);

// Reproduz o sorteio (supondo que devam ser sorteados 3 avaliadores)
$resultado = auditableDraw($seed, $items, 3);
print_r($resultado); // Deve ser idêntico ao resultado original registrado
```

Observação Importante:
A lista de avaliadores ($items) deve ser exatamente a mesma usada no sorteio original (mesmos IDs e mesma ordem). Se a ordem dos IDs for diferente, o resultado do shuffle será alterado. Para garantir consistência, recomenda-se ordenar a lista de IDs antes do sorteio (se aplicável).

Esta implementação garante total transparência e permite que desenvolvedores ou auditores validem o sorteio a qualquer momento, usando apenas os dados originais.

