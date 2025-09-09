# Como Funciona o Sorteio de Avaliadores e Sua Auditoria

## Explicação Simplificada
O sistema realiza sorteios de avaliadores de forma transparente e auditável. Usamos um "número de controle" único (chamado seed) gerado a partir de informações fixas da comissão (ID da configuração da fase, nome da comissão e número do sorteio). Esse número garante que o mesmo conjunto de dados sempre produzirá o mesmo resultado. Qualquer pessoa pode verificar o processo, bastando reunir as informações originais e repetir os passos do sorteio.

## Explicação Técnica para Auditoria em PHP
A implementação do sorteio auditável utiliza a função auditableDraw, que depende de um seed determinístico para inicializar o gerador de números pseudoaleatórios do PHP (mt_srand). O seed é criado a partir das seguintes variáveis:

`$evaluation_method_configuration_id` (ID da configuração da fase de avaliação);

`$committee_name` (nome da comissão);

`$draw_number` (número sequencial do sorteio na comissão).

### Passos para Auditoria:
#### 1. Recuperar os dados de entrada usados no sorteio:

ID da configuração da fase (`$evaluation_method_configuration_id`);

Nome da comissão (`$committee_name`);

Número do sorteio (`$draw_number`);

Lista original de IDs dos avaliadores (`$valuer_ids`).

#### 2. Gerar o seed original (exatamente como implementado):

```php
$seed = crc32($evaluation_method_configuration_id . $committee_name . $draw_number);
```

#### 3. Reproduzir o sorteio com a função auditableDraw:

```php
function auditableDraw(int $seed, array $valuer_ids, int $number_of_valuers) {
     // Ordena os IDs para garantir resultado consistente, independente da ordem de entrada
     sort($valuer_ids);
     
     mt_srand($seed); // Inicializa o gerador com o seed
     $shuffled_items = $valuer_ids;
     shuffle($shuffled_items); // Embaralha a lista deterministicamente
     
     return array_slice($shuffled_items, 0, $number_of_valuers); // Seleciona os N primeiros
 }
```

#### 4. Comparar o resultado com o registro armazenado no sistema.

### Por que é Auditável?
- O seed é derivado de dados imutáveis e públicos (ID da configuração, nome da comissão e número do sorteio);
- `mt_srand()` e `shuffle()` são determinísticos quando inicializados com o mesmo seed;
- A função shuffle do PHP usa o gerador Mersenne Twister, que é reproduzível com o mesmo seed.

### Exemplo de Código de Validação:
```php
// Dados de entrada (exemplo)
$evaluation_method_configuration_id = 456;
$committee_name = "Comissão de Teatro";
$draw_number = 2;
$valuer_ids = [50, 30, 10, 40, 20]; // Lista de IDs em ordem aleatória

// Gera o seed (exatamente como na implementação real)
$seed = crc32($evaluation_method_configuration_id . $committee_name . $draw_number);

// Reproduz o sorteio (supondo que devam ser sorteados 3 avaliadores)
$resultado = auditableDraw($seed, $valuer_ids, 3);
print_r($resultado); // Deve ser idêntico ao resultado original registrado
```

### Observação Importante:
A ordenação sort($valuer_ids) dentro da função garante que mesmo que os IDs sejam fornecidos em ordens diferentes (ex.: [50, 30, 10] ou [10, 30, 50]), o resultado final será sempre o mesmo, pois a randomização será aplicada sobre a mesma lista ordenada.

Esta implementação garante total transparência e permite que desenvolvedores ou auditores validem o sorteio a qualquer momento, usando apenas os dados originais.

